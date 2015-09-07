<?php

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class eZPlatformSearch implements ezpSearchEngine
{
    /**
     * @var \eZ\Publish\SPI\Search\Handler
     */
    protected $searchHandler;

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $searchEngine;

    public function __construct()
    {
        $serviceContainer = ezpKernel::instance()->getServiceContainer();

        $this->searchHandler = $serviceContainer->get( 'ezpublish.spi.search' );
        $this->repository = $serviceContainer->get( 'ezpublish.api.repository' );
        $this->searchEngine = $serviceContainer->getParameter( 'search_engine' );
    }

    /**
     * Whether a commit operation is required after adding/removing objects.
     *
     * @see commit()
     * @return bool
     */
    public function needCommit()
    {
        return method_exists( $this->searchHandler, 'commit' );
    }

    /**
     * Whether calling removeObject() is required when updating an object.
     *
     * @see removeObject()
     * @return bool
     */
    public function needRemoveWithUpdate()
    {
        return true;
    }

    /**
     * Adds object $contentObject to the search database.
     *
     * @param eZContentObject $contentObject Object to add to search engine
     * @param bool $commit Whether to commit after adding the object
     *
     * @return bool True if the operation succeed.
     */
    public function addObject( $contentObject, $commit = true )
    {
        // Indexing is not implemented in eZ Publish 5 legacy search engine
        if ( $this->searchEngine == 'legacy' )
        {
            $searchEngine = new eZSearchEngine();
            return $searchEngine->addObject( $contentObject, $commit );
        }

        $content = $this->repository->sudo(
            function ( Repository $repository ) use ( $contentObject )
            {
                return $repository->getContentService()->loadContent(
                    (int)$contentObject->attribute( 'id' )
                );
            }
        );

        $this->searchHandler->indexContent( $content );

        return true;
    }

    /**
     * Removes object $contentObject from the search database.
     *
     * @deprecated Since 5.0, use removeObjectById()
     *
     * @param eZContentObject $contentObject the content object to remove
     * @param bool $commit Whether to commit after removing the object
     *
     * @return bool True if the operation succeed.
     */
    public function removeObject( $contentObject, $commit = null )
    {
        return $this->removeObjectById( $contentObject->attribute( 'id' ), $commit );
    }

    /**
     * Removes a content object by Id from the search database.
     *
     * @since 5.0
     *
     * @param int $contentObjectId The content object to remove by id
     * @param bool $commit Whether to commit after removing the object
     *
     * @return bool True if the operation succeed.
     */
    public function removeObjectById( $contentObjectId, $commit = null )
    {
        // Indexing is not implemented in eZ Publish 5 legacy search engine
        if ( $this->searchEngine == 'legacy' )
        {
            $searchEngine = new eZSearchEngine();
            return $searchEngine->removeObjectById( $contentObjectId, $commit );
        }

        $this->searchHandler->deleteContent( $contentObjectId );

        if ( $commit && method_exists( $this->searchHandler, 'commit' ) )
        {
            $this->searchHandler->commit();
        }

        return true;
    }

    /**
     * Searches $searchText in the search database.
     *
     * @see supportedSearchTypes()
     *
     * @param string $searchText Search term
     * @param array $params Search parameters
     * @param array $searchTypes Search types
     *
     * @return array
     */
    public function search( $searchText, $params = array(), $searchTypes = array() )
    {
        $doFullText = true;
        $query = new LocationQuery();

        $criteria = array();

        if ( isset( $params['SearchDate'] ) && (int)$params['SearchDate'] > 0 )
        {
            $currentTimestamp = time();
            $dateSearchType = (int)$params['SearchDate'];

            $fromTimestamp = 0;
            if ( $dateSearchType === 1 )
            {
                // Last day
                $fromTimestamp = $currentTimestamp - 86400;
            }
            else if ( $dateSearchType === 2 )
            {
                // Last week
                $fromTimestamp = $currentTimestamp - ( 7 * 86400 );
            }
            else if ( $dateSearchType === 3 )
            {
                // Last month
                $fromTimestamp = $currentTimestamp - ( 30 * 86400 );
            }
            else if ( $dateSearchType === 4 )
            {
                // Last three months
                $fromTimestamp = $currentTimestamp - ( 3 * 30 * 86400 );
            }
            else if ( $dateSearchType === 5 )
            {
                // Last year
                $fromTimestamp = $currentTimestamp - ( 365 * 86400 );
            }

            $criteria[] = new Criterion\DateMetadata(
                Criterion\DateMetadata::CREATED,
                Criterion\Operator::GTE,
                $fromTimestamp
            );
        }

        if ( isset( $params['SearchSectionID'] ) && (int)$params['SearchSectionID'] > 0 )
        {
            $criteria[] = new Criterion\SectionId( (int)$params['SearchSectionID'] );
        }

        if ( isset( $params['SearchContentClassID'] ) && (int)$params['SearchContentClassID'] > 0 )
        {
            $criteria[] = new Criterion\ContentTypeId( (int)$params['SearchContentClassID'] );

            if ( isset( $params['SearchContentClassAttributeID'] ) && (int)$params['SearchContentClassAttributeID'] > 0 )
            {
                $classAttribute = eZContentClassAttribute::fetch( $params['SearchContentClassAttributeID'] );
                if ( $classAttribute instanceof eZContentClassAttribute )
                {
                    $criteria[] = new Criterion\Field(
                        $classAttribute->attribute( 'identifier' ),
                        null,
                        $searchText
                    );

                    $doFullText = false;
                }
            }
        }

        if ( isset( $params['SearchSubTreeArray'] ) && !empty( $params['SearchSubTreeArray'] ) )
        {
            $subTreeArrayCriteria = array();
            foreach ( $params['SearchSubTreeArray'] as $nodeId )
            {
                $node = eZContentObjectTreeNode::fetch( $nodeId );
                $subTreeArrayCriteria[] = $node->attribute( 'path_string' );
            }

            $criteria[] = new Criterion\Subtree( $subTreeArrayCriteria );
        }

        if ( $doFullText )
        {
            $criteria[] = new Criterion\FullText( $searchText );
        }

        $query->query = new Criterion\LogicalAnd( $criteria );

        $query->limit = isset( $params['SearchLimit'] ) ? (int)$params['SearchLimit'] : 10;
        $query->offset = isset( $params['SearchOffset'] ) ? (int)$params['SearchOffset'] : 0;

        $searchResult = $this->repository->getSearchService()->findLocations( $query, array(), false );

        $nodeIds = array();
        foreach ( $searchResult->searchHits as $searchHit )
        {
            $nodeIds[] = $searchHit->valueObject->id;
        }

        $nodes = eZContentObjectTreeNode::fetch( $nodeIds );
        if ( $nodes instanceof eZContentObjectTreeNode )
        {
            $nodes = array( $nodes );
        }
        else if ( !is_array( $nodes ) )
        {
            $nodes = array();
        }

        return array(
            'SearchResult' => $nodes,
            'SearchCount' => $searchResult->totalCount,
            'StopWordArray' => array()
        );
    }

    /**
     * Returns an array describing the supported search types by the search engine.
     *
     * @see search()
     * @return array
     */
    public function supportedSearchTypes()
    {
        return array(
            'types' => array(
                array(
                    'type' => 'fulltext',
                    'subtype' => 'text',
                    'params' => array( 'value' )
                )
            ),
            'general_filter' => array()
        );
    }

    /**
     * Commit the changes made to the search engine.
     *
     * @see needCommit()
     */
    public function commit()
    {
        if ( method_exists( $this->searchHandler, 'commit' ) )
        {
            $this->searchHandler->commit();
        }
    }
}
