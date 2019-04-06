webix.ready(function() {
    //initOrder();
    getList();
    initContainer();
    initExport();
    initDetail(1);
    initPivotTable();
});

function initOrder() {
    dtable = new webix.ui({
        container:"listProduct",
        view:"datatable",
        id: "listProduct",
        autoheight:true,
        autowidth:true,
        columns:[
            { id:"proId",   header:"Mã SP",     adjust:true},
            { id:"proName",    header:"Tên sản phẩm" ,  adjust:true },
            { id:"price",   header:"Giá",   adjust:true},
            { id:"catId",   header:"Danh mục",     adjust:true},
            { id:"quantity",   header:"Số lượng tồn kho",   adjust:true},
            {id:"tinyDes", header: "Mô tả", adjust: true}
        ],
        data:[]
    });
}
var parser = webix.Date.strToDate("%d.%M.%Y");

function initContainer() {
    webix.ui({
        container: 'containerProduct',
        autoheight:true,
        autowidth: true,
        cols:[
            {
                rows: [
                    { view: "template", template: "Danh sách sản phẩm <button id='btnExport' class='webix_button webixtype_base' style='float: right;background: #3c8dbc;margin-top: 5px;color: #fff;padding-left: 5px;padding-right: 5px;'>Xuất file excel</button> <button id='btnSheet' class='webix_button webixtype_base' style='float: right;background: #3c8dbc;margin-top: 5px;color: #fff;padding-left: 5px;padding-right: 5px;'>Tạo excel online</button>", type: "header" },
                    { view: "datatable", id: "listProduct",
                        height: 250,
                        autowidth:true,
                        select:"row",                        
                        columns:[
                            { id:"proId",   header:"Mã SP",  adjust:true, sort:"int"},
                            { id:"proName",    header:"Tên sản phẩm" ,  adjust:true , sort:"string"},
                            { id:"price",   header:"Giá",   adjust:true, sort:"int"},
                            { id:"catId",   header:"Danh mục",     adjust:true, sort:"int"},
                            { id:"quantity",   header:"Số lượng tồn kho",   adjust:true, sort:"int"},
                            { id:"dayAdd", header: "Ngày nhập hàng", adjust: true, sort:"date"},
                            { id:"view", header: "Số lượt xem", adjust: true, sort:"int"}
                        ],
                        on:{
                            "onItemClick":function(id, e, trg){
                                // //id.column - column id
                                // //id.row - row id
                                // webix.message("Click on row: " + id.row+", column: " + id.column);
                                var product = $$('listProduct').getItem(id);
                                initDetail(product.proId);
                                initDraw(product.proId);
                            },
                        },
                        autoConfig:true,

                        data:[]
                    },
                    { view:"datepicker", type:"month", label:"Phân tích doanh thu tháng : ", name:"start", value: new Date(),
                        format: "Tháng %m Năm %Y", stringResult: true, labelWidth: 250, width: 600 , id: 'monthAnalyze'},

                    // Pivot Table
                    {
                        id:"pivot",
                        view:"pivot",
                        footer: true,
                        max: true,
                        datatable:{
                            rowHeight:25,
                            rowLineHeight:25,
                            headerRowHeight:25
                        },
                        height: 300,
                        format: function (value) {
                            // value && value != "0" ? parseFloat(value).toFixed(0) : value
                            if(value && value != "0"){
                                let cost = parseFloat(value).toFixed(0);
                                // console.log(cost);
                                return (formatNumber(cost)+' VNĐ');
                            }

                        },
                        popup:{
                            on: {
                                onViewInit: function(name, config){
                                    config.position = "center";
                                }
                            }
                        },
                        structure: {
                            rows: ["orderDate", "orderID"],
                            // columns: ["year"],
                            values: [{ name:"total", operation:["sum"]}],
                            filters:[]
                        },
                        on:{
                            onItemClick:function (id, e, n) {
                                if (id.column === "name") return;
                                var datatable = this.$$('data').getItem(id.row);
                                webix.ajax('./detail-order.php?id='+datatable.name, function (data) {
                                    //popup
                                    console.log(data);
                                    webix.ui({
                                        view:"window",
                                        id: 'detailOrder',
                                        height:250,
                                        position: "center",
                                        move:true,
                                        head:{
                                            view:"toolbar", cols:[
                                                {view:"label", label: "Chi tiết doanh thu" },
                                                { view:"button", label: 'Đóng', width: 100, align: 'right', click:"$$('detailOrder').close();"}
                                            ]
                                        },
                                        body:{
                                            view: "datatable",
                                            id: 'detailTable',
                                            width: 700,
                                            data: data,
                                            select:"row",
                                            columns:[
                                                { id:"orderId",   header:"Mã đơn hàng",     adjust:true},
                                                { id:"proID",    header:"ID sản phẩm" ,  adjust:true },
                                                { id:"proName",    header:"Tên sản phẩm" ,  adjust:true },
                                                { id:"price",   header:"Giá 1 cái",   adjust:true},
                                                { id:"quantity",   header:"Số lượng mua",     adjust:true},
                                                { id:"amount",   header:"Tổng tiền",   adjust:true},
                                            ],
                                            on:{
                                                onBeforeLoad:function(){
                                                    this.showOverlay("Loading...");
                                                },
                                                onAfterLoad:function(){
                                                    if(!this.count()){
                                                        this.showOverlay("No data");
                                                    }
                                                    else{
                                                        this.hideOverlay();
                                                    }
                                                },
                                                onItemClick:function(id, e, trg){
                                                    // //id.column - column id
                                                    // //id.row - row id
                                                    // webix.message("Click on row: " + id.row+", column: " + id.column);
                                                    $$('detailOrder').close();
                                                    var product = $$('detailTable').getItem(id);
                                                    initDetail(product.proID);
                                                    initDraw(product.proID);
                                                },

                                            }

                                        }
                                    }).show();
                                });
                            }                       
                        }

                    }

                ],
            },
            {
                rows: [
                    { view: "template", template: "Chi tiết sản phẩm", type: "header" },
                    { view:"template",
                        id: 'detail',
                        height: 360,
                        autowidth: true,
                        select:true,
                        template:
                            "<table class=\"tg\" style=\"undefined;table-layout: fixed; height: 250px\">\n" +
                            "<colgroup>\n" +
                            "<col style=\"width: 150px\">\n" +
                            "<col style=\"width: 60%\">\n" +
                            "</colgroup>\n" +
                            "  <tr>\n" +
                            "    <th class=\"tg-0lax\" colspan=\"2\">#proName#</th>\n" +
                            "  </tr>\n" +
                            "  <tr>\n" +
                            "    <td class=\"tg-0lax\">Mã sản phẩm: </td>\n" +
                            "    <td class=\"tg-0lax\">#proId#</td>\n" +
                            "  </tr>\n" +
                            "  <tr>\n" +
                            "    <td class=\"tg-0lax\">Giá sản phẩm: </td>\n" +
                            "    <td class=\"tg-0lax\">#price#</td>\n" +
                            "  </tr>\n" +
                            "  <tr>\n" +
                            "    <td class=\"tg-0lax\">Danh mục: </td>\n" +
                            "    <td class=\"tg-0lax\">#catId#</td>\n" +
                            "  </tr>\n" +
                            "  <tr>\n" +
                            "    <td class=\"tg-0lax\">Số lượng tồn kho: </td>\n" +
                            "    <td class=\"tg-0lax\">#quantity#</td>\n" +
                            "  </tr>\n" +
                            "  <tr>\n" +
                            "    <td class=\"tg-0lax\">Ngày nhập kho: </td>\n" +
                            "    <td class=\"tg-0lax\">#dayAdd#</td>\n" +
                            "  </tr>\n" +
                            "  <tr>\n" +
                            "    <td class=\"tg-0lax\">Số lượt xem: </td>\n" +
                            "    <td class=\"tg-0lax\">#view#</td>\n" +
                            "  </tr>\n" +
                            "</table>",

                    },
                    { view: "template", template: "Thống kê số lượng mua theo ngày", type: "header" },
                    {
                        view:"chart",
                        id: 'detailChart',
                        height: 200,
                        autowidth: true,
                        type:"line",
                        xAxis:{
                            template:"#proName#"
                        },
                        yAxis:{
                            start:0,
                            step:2,
                            end: 10
                        },
                        series:[
                            {
                                value:"#proId#",
                                item:{
                                    borderColor: "#1293f8",
                                    color: "#ffffff"
                                },
                                line:{
                                    color:"#1293f8",
                                    width:3
                                },
                                tooltip:{
                                    template:"#proName#"
                                }
                            }
                        ],

                    }
                ],
            },
        ]
    });
}

function initDetail(id){
    webix.ajax('./list-product.php?opt=2&id='+id, function (data) {
        $$('detail').parse(data);
    });
}

function initDraw(id){
    webix.ajax('./graph.php?id='+id, function(data){
        console.log(data);
        $$('detailChart').clearAll();
        $$('detailChart').parse(data);
    });
}

function getList() {
    webix.ajax('./list-product.php?opt=1', function (data) {
        $$('listProduct').parse(data);
    });
}

function initExport() {
    $('#btnExport').click(function () {
        let filename = 'Danh sách sản phẩm';
        let name = 'Danh sách sản phẩm';
        webix.toExcel($$("listProduct"), {
            filename: filename,
            name: name,
            filterHTML: true,
            styles:true,
        });
    });

    $('#btnSheet').click(function () {
        var win = window.open("/admin/page/order-webix/excel.php", '_blank');
        win.focus();
    });
}

function initPivotTable() {
    //webix.ajax('./pivot-data.php?month=')
    var yearFormat = webix.Date.dateToStr("%Y");
    var monthFormat = webix.Date.dateToStr("%m");

    var monthDefault = monthFormat(new Date());
    var yearDefault = yearFormat(new Date());

    webix.ajax('./pivot-data.php?month='+monthDefault+'&year='+yearDefault, function (data) {
        console.log(data);
        $$('pivot').clearAll();
        $$('pivot').parse(data);
    });

    $$('monthAnalyze').attachEvent("onChange", function(newv, oldv){
        var year = yearFormat(newv);
        var month = monthFormat(newv);

        webix.ajax('./pivot-data.php?month='+month+'&year='+year, function (data) {
            console.log(data);
            $$('pivot').clearAll();
            $$('pivot').parse(data);
        });
    });
}

function formatNumber(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".") + (parts[1] ? "." + parts[1] : "");
}

