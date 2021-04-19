<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/metrics">
        <html>
            <body>
                <style>
                    body { font-family: consolas; }
                    ul.methods { -webkit-column-count: 3; }
                    sup { margin: 0 0.2em; font-weight: normal; padding: 0.4em 0.3em; background: gainsboro; color: gray; }
                    .position-top-20 { color: red; }
                    .position-top-10 { font-weight: bold; }
                    .ccn-is-low { color: gray;  font-size: 80%; }
                    .ccn-is-low sup { display: none; }
                    p { margin: 0; padding: 0; margin-bottom: 0.2em; }
                </style>
                <h1>
                    Project

                    <sup title="Cyclomatic Complexity Number">
                        cyclo: <xsl:value-of select="@ccn"/>
                    </sup>
                    <sup title="Number of Method or Function Calls">calls:
                        <xsl:value-of select="@calls"/>
                    </sup>
                    <sup title="classes">abstract: <xsl:value-of select="@clsa"/></sup>
                    <sup title="classes">concrete: <xsl:value-of select="@clsc"/></sup>
                    <sup>interfaces: <xsl:value-of select="@noi"/></sup>
                    <sup>methods: <xsl:value-of select="@nom"/></sup>

                </h1>
                <xsl:for-each select="package">
                    <h2>
                        <xsl:value-of select="@name"/>
                        <sup title="Google PageRank applied on Packages and Classes.
        Classes with a high value should be tested frequently.">code rank:
                            <xsl:value-of select="@cr"/></sup>
                    </h2>
                    <ul class="classes">
                        <xsl:apply-templates select="class">
                            <xsl:sort
                                    select="@wmc"
                                    data-type="number"
                                    order="descending"
                            />
                        </xsl:apply-templates>
                    </ul>
                </xsl:for-each>
            </body>
        </html>
    </xsl:template>
    <xsl:template match="class">
        <h3>
            <xsl:attribute name="class">
                <xsl:if test="0.1 > position() div count(../class)">
                    position-top-10
                </xsl:if>
                <xsl:if test="0.2 > position() div count(../class)">
                    position-top-20
                </xsl:if>
            </xsl:attribute>
            <xsl:value-of select="@name"/>
            <sup title="Sum of the complexities of all declared
          methods and constructors of class.">weighted method count: <xsl:value-of select="@wmc"/>
            </sup>
            <sup title="Number of unique outgoing
          dependencies to other artifacts of the same type">outgoing coupling: <xsl:value-of select="@cbo"/>
            </sup>
        </h3>
        <ul class="methods">
            <xsl:apply-templates select="method">
                <xsl:sort
                        select="@npath"
                        data-type="number"
                        order="descending"
                />
            </xsl:apply-templates>
        </ul>
    </xsl:template>
    <xsl:template match="method">
        <p>
            <xsl:attribute name="class">
                <xsl:if test="0.1 > position() div count(../method)">
                    position-top-10
                </xsl:if>
                <xsl:if test="0.2 > position() div count(../method)">
                    position-top-20
                </xsl:if>
                <xsl:if test="1 >= @ccn">
                    ccn-is-low
                </xsl:if>
            </xsl:attribute>
            <xsl:value-of select="@name"/>
            <sup title="Cyclomatic Complexity Number">cyclo: <xsl:value-of select="@ccn"/>
            </sup>
            <sup title="NPath Complexity">npath: <xsl:value-of select="@npath"/>
            </sup>
        </p>
    </xsl:template>
</xsl:stylesheet>