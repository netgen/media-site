<?php

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class eZPlatformSearch implements ezpSearchEngine
{
    /**
     * Whether a commit operation is required after adding/removing objects.
     *
     * @see commit()
     * @return bool
     */
    public function needCommit()
    {
        return false;
    }

    /**
     * Whether calling removeObject() is required when updating an object.
     *
     * @see removeObject()
     * @return bool
     */
    public function needRemoveWithUpdate()
    {
        return false;
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
        return true;
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
        $serviceContainer = ezpKernel::instance()->getServiceContainer();
        $searchService = $serviceContainer->get( 'ezpublish.api.service.search' );

        $query = new LocationQuery();

        $criteria = array( new Criterion\FullText( $searchText ) );

        if ( isset( $params['SearchSectionID'] ) && $params['SearchSectionID'] > 0 )
        {
            $criteria[] = new Criterion\SectionId( $params['SearchSectionID'] );
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

        $query->query = new Criterion\LogicalAnd( $criteria );

        $query->limit = isset( $params['SearchLimit'] ) ? $params['SearchLimit'] : 10;
        $query->offset = isset( $params['SearchOffset'] ) ? $params['SearchOffset'] : 0;

        $searchResult = $searchService->findLocations( $query, array(), false );

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
    }
}
