<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

    <xsl:output method="html" encoding="UTF-8"/>

    <xsl:template match="/">

        <html>
            <head>
                <title>Jookide tabel</title>
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th,td { border: 1px solid #ccc; padding: 6px; text-align: left; }
                    th { background: #f0f0f0; }
                </style>
            </head>

            <body>
                <h2>Jookide nimekiri (XSLT abil)</h2>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nimi</th>
                            <th>Kogus (ml)</th>
                            <th>Tops</th>
                            <th>Maksmine</th>
                            <th>Kategooria</th>
                        </tr>
                    </thead>

                    <tbody>
                        <xsl:for-each select="joogimenüü/grupp/joogid/jook">
                            <tr>
                                <td><xsl:value-of select="@id"/></td>
                                <td><xsl:value-of select="jooginimi"/></td>
                                <td><xsl:value-of select="kogus"/></td>
                                <td><xsl:value-of select="topsitüüp"/></td>
                                <td><xsl:value-of select="maksmisviis"/></td>
                                <td><xsl:value-of select="../../@id"/></td>
                            </tr>
                        </xsl:for-each>
                    </tbody>
                </table>

            </body>
        </html>

    </xsl:template>

</xsl:stylesheet>
