<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <div>
        <h2 class="h3 ms-4" id="acName">TSLA</h2>

        <div id="wrapper">
            <div id="table" class="table-div">
                <table id="series-table" class="table">
                    <thead>
                        <tr class="table-primary">			  
                        <th scope="col">Data</th>
                        <th scope="col">Abertura</th>
                        <th scope="col">Fecho</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>	
            </div>
                
            <div id="chart-column" class="column-chart"></div>
        </div>
            
        <div class="col-md-12">
            <div id="chart-line" class="line-chart"></div>
        </div>
    </div>
</main>
<script type="text/javascript" src="./js/alphavantage.js"></script>	