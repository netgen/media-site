<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
        version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
        xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"
        xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
        exclude-result-prefixes="xhtml custom image">

    <xsl:output method="html" indent="yes" encoding="UTF-8" />

    <xsl:template match="custom[@name='quote']">
        <div>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="@custom:align != ''">
                        <xsl:value-of select="concat( 'object-', @custom:align )" />
                    </xsl:when>
                    <xsl:otherwise>left</xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>

            <blockquote>
                <div>
                    <xsl:attribute name="class">blockquote-body</xsl:attribute>
                    <xsl:apply-templates />
                </div>

                <xsl:if test="@custom:author != ''">
                    <small>
                        <xsl:value-of select="@custom:author" />
                    </small>
                </xsl:if>
            </blockquote>
        </div>
    </xsl:template>

    <xsl:template match="table">
        <xsl:choose>
            <xsl:when test="@custom:responsive != ''">
            <div class="table-responsive">
                <xsl:element name="table" use-attribute-sets="ngmore-table">
                    <xsl:if test="@custom:caption != ''">
                        <caption>
                            <xsl:value-of select="@custom:caption" />
                        </caption>
                    </xsl:if>

                    <xsl:apply-templates/>
                </xsl:element>
            </div>
            </xsl:when>
            <xsl:otherwise>
                <xsl:element name="table" use-attribute-sets="ngmore-table">
                    <xsl:if test="@custom:caption != ''">
                        <caption>
                            <xsl:value-of select="@custom:caption" />
                        </caption>
                    </xsl:if>

                    <xsl:apply-templates/>
                </xsl:element>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:attribute-set name="ngmore-table">
        <xsl:attribute name="class">
            <xsl:choose>
                <xsl:when test="@class != ''">
                    <xsl:value-of select="concat( 'table ', @class )" />
                    <xsl:if test="@align != ''">
                        <xsl:value-of select="concat( ' object-', @align )" />
                    </xsl:if>
                </xsl:when>
                <xsl:otherwise>table</xsl:otherwise>
            </xsl:choose>
        </xsl:attribute>

        <xsl:attribute name="style">
            <xsl:if test="@width != '100%'">
                <xsl:value-of select="concat( 'width:', @width, ';' )" />
            </xsl:if>
            <xsl:if test="@border != '0'">
                <xsl:value-of select="concat( 'border:', @border, 'px solid;' )" />
            </xsl:if>
        </xsl:attribute>
    </xsl:attribute-set>

    <xsl:template match="td | th">
        <xsl:copy>
            <xsl:choose>
                <xsl:when test="@valign">
                    <xsl:attribute name="style">vertical-align: <xsl:value-of select="@valign" />;</xsl:attribute>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:attribute name="style">vertical-align: top;</xsl:attribute>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="@xhtml:colspan">
                <xsl:attribute name="colspan">
                    <xsl:value-of select="@xhtml:colspan" />
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="@xhtml:rowspan">
                <xsl:attribute name="rowspan">
                    <xsl:value-of select="@xhtml:rowspan" />
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="@xhtml:width">
                <xsl:attribute name="width">
                    <xsl:value-of select="@xhtml:width" />
                </xsl:attribute>
            </xsl:if>
            <xsl:copy-of select="@class"/>
            <xsl:copy-of select="@align"/>
            <xsl:apply-templates/>
        </xsl:copy>
    </xsl:template>

</xsl:stylesheet>
