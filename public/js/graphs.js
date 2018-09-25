/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    
    var ac_state = "ac_state";
    var ac_cur = "ac_cur";
    
    var ac_state_graphs = document.getElementsByClassName(ac_state);
    var ac_cur_graphs = document.getElementsByClassName(ac_cur);
    
    
    //=================== CURRENTS_AC graphs initial load =====================
    for(var x = 0; x < ac_cur_graphs.length; x++){
        var module = ac_cur_graphs[x].getAttribute("module");
        var id = ac_cur_graphs[x].getAttribute("id");
         $.ajax({
            type:"POST",
            url:"/device_controller/graphs/CURRENTS_AC_",
            data:{mode:"last_day",module: module},
            success:function(result){
//                console.log(result);
//                $("#text").append(result);
                var parsedResults = JSON.parse(result);
                var trace1 = {
                    type: "scatter",
                    name: 'rele1',
                    x: parsedResults[0],
                    y: parsedResults[1][0]
                };
                var trace2 = {
                    type: "scatter",
                    name: 'rele2',
                    x: parsedResults[0],
                    y: parsedResults[1][1]
                };
                var trace3 = {
                    type: "scatter",
                    name: 'rele3',
                    x: parsedResults[0],
                    y: parsedResults[1][2]
                };
                var trace4 = {
                    type: "scatter",
                    name: 'rele4',
                    x: parsedResults[0],
                    y: parsedResults[1][3]
                };
                var data = [trace1, trace2, trace3, trace4];
                var layout = {
                    title: 'CURRENTS_AC modul '+module,
                };

                Plotly.newPlot(id,data,layout);
            }
        });
        
        //zapis random data do grafu v intervale
        var interval = setInterval(function() {
            $.ajax({
                type:"POST",
                url:"/device_controller/graphs/CURRENTS_AC_",
                data:{mode:"refresh",module: module},
                success:function(result){
                    console.log(result);
//                    $("#text").append(result);
                    var parsedResults = JSON.parse(result);
                    Plotly.extendTraces(id,{
                        x:[data[0],data[0],data[0],data[0]],
                        y:[data[1][0],data[1][1],data[1][2],data[1][3]]
                    },['rele1','rele2','rele3','rele4']);
                }
            });
        }, 10000);
    }
    
    function rand() {
        return Math.random();
    }

    //============================= GRAF 1 ====================================
//    Plotly.plot('graph', [{
//      y: [1,2,3].map(rand)
//    }, {
//      y: [1,2,3].map(rand)
//    }]);
//
//    var cnt = 0;
//    
//    //zapis random data do grafu v intervale
//    var interval = setInterval(function() {
//
//      Plotly.extendTraces('graph', {
//        y: [[rand()], [rand()]]
//      }, [0, 1])
//
//      if(cnt === 100) clearInterval(interval);
//    }, 300);
//    
    $("#testButton").on('click', function(){
        $.ajax({
            type:"POST",
            url:"graphs/fetch",
            data:{cname:"clustername"},
            success:function(result){
                $("#text").append(result);
            }
        });
    });
    
    //============================ GRAF CURRENTS_AC =====================================
    function parseX(data){
        return data["time"];
    }
    function parseY(data){
        return data["data"];
    }
    var layout = {
        title: 'Basic Time Series',
    };
//    
//    Plotly.newPlot('graph2',[{
//        x: ['2018-4-04 22:23:00', '2018-4-05 22:23:00', '2018-4-06 22:23:00'],
//        y: [1, 3, 6],
//        type: 'scatter'
//    },{
//        x: ['2018-4-04 22:23:00', '2018-4-05 22:23:00', '2018-4-06 22:23:00'],
//        y: [2, 3, 9],
//        type: 'scatter'
//    },{
//        x: ['2018-4-04 22:23:00', '2018-4-05 22:23:00', '2018-4-06 22:23:00'],
//        y: [4, 5, 7],
//        type: 'scatter'
//    },{
//        x: ['2018-4-04 22:23:00', '2018-4-05 22:23:00', '2018-4-06 22:23:00'],
//        y: [2, 4, 6],
//        type: 'scatter'
//    }],layout);
//    $.ajax({
//            type:"POST",
//            url:"graphs/CURRENTS_AC_",
//            data:{mode:"lasthour"},
//            success:function(result){
//                // TODO extend graph data;
//                var data = JSON.parse(result);
////                 $("#text").append(result);
////                 $("#text").append(data.map(parseY));
////                 var arrayX = data.map(parseX);
////                 var arrayY = data.map(parseY);
//                 console.log(data[0]);
//                 console.log(data);
//                Plotly.newPlot('graph2',{
//                    x:[data[0],data[0],data[0],data[0]],
//                    y:[data[1][0],data[1][1],data[1][2],data[1][3]]
//                },[0,1,2,3]);
//            }
//       }); 

    $("#testGraph").on('click',function(){
       $.ajax({
            type:"POST",
            url:"graphs/CURRENTS_AC_",
            success:function(result){
                // TODO extend graph data;
                var data = JSON.parse(result);
//                 $("#text").append(result);
//                 $("#text").append(data.map(parseY));
//                 var arrayX = data.map(parseX);
//                 var arrayY = data.map(parseY);
                 console.log(data[0]);
                 console.log(data[1][0]);
//                Plotly.extendTraces('graph2',{
//                    x:[['2019-1-4 22:23:00','2019-2-4 22:23:00'],['2019-1-4 22:23:00','2019-2-4 22:23:00'],['2019-1-4 22:23:00','2019-2-4 22:23:00'],['2019-1-4 22:23:00','2019-2-4 22:23:00']],
//                    y:[[0.01,7],[7,6],[6,5],[5,4]]
//                },[0,1,2,3]);
                Plotly.extendTraces('graph2',{
                    x:[data[0],data[0],data[0],data[0]],
                    y:[data[1][0],data[1][1],data[1][2],data[1][3]]
                },[0,1,2,3]);
            }
       }); 
    });
    

})
