function createPieChart(id,labels,data){

new Chart(document.getElementById(id),{

type:'pie',

data:{
labels:labels,
datasets:[{
data:data
}]
}

});

}

const total = data.reduce((a, b) => a + b, 0);

const ctx = document.getElementById('pieChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {

                        let value = context.raw;
                        let total = context.dataset.data.reduce((a,b)=>a+b,0);

                        if(total === 0){
                            return context.label + ": 0%";   // ⭐ FIX
                        }

                        let percentage = ((value / total) * 100).toFixed(1);
                        return context.label + ": " + percentage + "%";
                    }
                }
            }
        }
    }
});


function createBarChart(id,labels,data){

new Chart(document.getElementById(id),{

type:'bar',

data:{
labels:labels,
datasets:[{
label:'Amount',
data:data
}]
}

});

}