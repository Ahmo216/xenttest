<div id="tabs">
    <ul>
        <li><a href="#tabs-1">{|Debuginfos|}</a></li>
        <li><a href="#tabs-2">{|Rechnunsupload|}</a></li>
        <li><a href="#tabs-3">{|Orderinfos|}</a></li>
    </ul>
<!-- ende gehort zu tabview -->
    <!-- erstes tab -->
    <div id="tabs-1">
        [MESSAGE]
        <form method="post">
            <fieldset>
                <legend>{|Order|}</legend>
                <div>
                    <input type="submit" name="debugTest" value="debugTest" />
                </div>
                <div>
                    <input type="submit" name="checkFulfilledShipments" value="checkFulfilledShipments" />
                </div>
                <div>
                    <input type="submit" name="checkReportsForReturnOrder" value="checkReportsForReturnOrder" />
                </div>
                <div>
                    <input type="submit" name="GetFeedSubmissionList" value="GetFeedSubmissionList" />
                </div>
                <div>
                    <label for="GetReportListType">{|Type|}:</label>
                    <input type="text" id="GetReportListType" name="GetReportListType" />
                    <input type="submit" name="GetReportList" value="GetReportList" />
                </div>
                <div>
                    <input type="submit" name="GetReportRequestList" value="GetReportRequestList" />
                </div>
                <div>
                    <input type="submit" name="checkFulfilledShipments" value="checkFulfilledShipments" />
                </div>
                <div>
                    <label for="GetReportReportId">{|ReportId|}:</label>
                    <input type="text" id="GetReportReportId" name="GetReportReportId" />
                    <input type="submit" name="GetReport" value="GetReport" />
                </div>
                <div>
                    <label for="GetFeedSubmissionFeedSubmissionId">{|FeedSubmissionId|}:</label>
                    <input type="text" id="GetFeedSubmissionFeedSubmissionId" name="GetFeedSubmissionFeedSubmissionId" />
                    <input type="submit" name="GetFeedSubmissionResult" value="GetFeedSubmissionResult" />
                </div>
                <div>
                    <label for="RequestReportType">{|RequestType|}:</label>
                    <input type="text" id="RequestReportType" name="RequestReportType" />
                    <label for="RequestReportMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="RequestReportMarketplace" name="RequestReportMarketplace" />
                    <input type="submit" name="RequestReport" value="RequestReport" />
                </div>
                <div>
                    <label for="GetCompetitivePricingForASINAsin">{|ASIN|}:</label>
                    <input type="text" id="GetCompetitivePricingForASINAsin" name="GetCompetitivePricingForASINAsin" />
                    <label for="GetCompetitivePricingForASINMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="GetCompetitivePricingForASINMarketplace" name="GetCompetitivePricingForASINMarketplace" />
                    <input type="submit" name="GetCompetitivePricingForASIN" value="GetCompetitivePricingForASIN" />
                </div>
                <div>
                    <label for="GetMatchingProductAsin">{|ASIN|}:</label>
                    <input type="text" id="GetMatchingProductAsin" name="GetMatchingProductAsin" />
                    <label for="GetMatchingProductMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="GetMatchingProductMarketplace" name="GetMatchingProductMarketplace" />
                    <input type="submit" name="GetMatchingProduct" value="GetMatchingProduct" />
                </div>
                <div>
                    <label for="GetMatchingProductForIdType">{|Type|}:</label>
                    <select id="GetMatchingProductForIdType" name="GetMatchingProductForIdType">
                        <option>ASIN</option>
                        <option>SellerSKU</option>
                        <option>EAN</option>
                        <option>ISBN</option>
                    </select>
                    <label for="GetMatchingProductForIdAsin">{|ASIN|}:</label>
                    <input type="text" id="GetMatchingProductForIdAsin" name="GetMatchingProductForIdAsin" />
                    <label for="GetMatchingProductForIdMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="GetMatchingProductForIdMarketplace" name="GetMatchingProductForIdMarketplace" />
                    <input type="submit" name="GetMatchingProductForId" value="GetMatchingProductForId" />
                </div>
                <div>
                    <label for="ListMatchingProductsQuery">{|Query|}:</label>
                    <input type="text" id="ListMatchingProductsQuery" name="ListMatchingProductsQuery" />
                    <label for="ListMatchingProductsMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="ListMatchingProductsMarketplace" name="ListMatchingProductsMarketplace" />
                    <input type="submit" name="ListMatchingProducts" value="ListMatchingProducts" />
                </div>

                <div>
                    <label for="requestRootBrowsetreeMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="requestRootBrowsetreeMarketplace" name="requestRootBrowsetreeMarketplace" />
                    <input type="submit" name="requestRootBrowsetree" value="requestRootBrowsetree" />
                </div>
                <div>
                    <label for="requestCompleteBrowsetreeMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="requestCompleteBrowsetreeMarketplace" name="requestCompleteBrowsetreeMarketplace" />
                    <label for="requestCompleteBrowsetreeNodeId">{|BrowseNodeId|}:</label>
                    <input type="text" id="requestCompleteBrowsetreeNodeId" name="requestCompleteBrowsetreeNodeId" />
                    <input type="submit" name="requestCompleteBrowsetree" value="requestCompleteBrowsetree" />
                </div>
                <div>
                    <label for="parseRootBrowsetreeMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="parseRootBrowsetreeMarketplace" name="parseRootBrowsetreeMarketplace" />
                    <input type="submit" name="parseRootBrowsetree" value="parseRootBrowsetree" />
                </div>

                <div>
                    <label for="parseCompleteBrowsetreeMarketplace">{|Marktplatz|}:</label>
                    <input type="text" id="parseCompleteBrowsetreeMarketplace" name="parseCompleteBrowsetreeMarketplace" />
                    <label for="parseCompleteBrowsetreeBrowseNodeId">{|BrowseNodeId|}:</label>
                    <input type="text" id="parseCompleteBrowsetreeBrowseNodeId" name="parseCompleteBrowsetreeBrowseNodeId" />
                    <input type="submit" name="parseCompleteBrowsetree" value="parseCompleteBrowsetree" />
                </div>
                <div>
                    <input type="submit" name="checkBrowseTreeReportsToImport" id="checkBrowseTreeReportsToImport" value="checkBrowseTreeReportsToImport" />
                </div>
                <div>
                    <input type="submit" name="checkBrowseTreeReportsCronjob" id="checkBrowseTreeReportsCronjob" value="checkBrowseTreeReportsCronjob" />
                </div>
                <div>
                    <input type="submit" name="checkTreeReportToRequest" id="checkTreeReportToRequest" value="checkTreeReportToRequest" />
                </div>
                <div>
                    <input type="submit" name="ProductGetServiceStatus" id="ProductGetServiceStatus" value="ProductGetServiceStatus" />
                </div>
            </fieldset>
        </form>
        <fieldset>
            <legend>{|Ergebnis|}</legend>
            [TAB1]
        </fieldset>
        [TAB1NEXT]
    </div>

    <div id="tabs-2">
        [MESSAGE]
        [TAB2]
    </div>

    <div id="tabs-3">
        [MESSAGE]
        [TAB3]
    </div>
<!-- tab view schließen -->
</div>

