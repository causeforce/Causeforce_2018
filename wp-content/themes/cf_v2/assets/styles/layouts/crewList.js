<script type="text/javascript">

var divisionsURL = 'https://secure2.convio.net/cfrau/site/CRTeamraiserAPI?method=getTeamsByInfo&api_key=cfrau&v=1.0&fr_id=1310&response_format=json&list_page_size=500';
var fundRaisingURL = 'https://secure2.convio.net/cfrau/site/CRTeamraiserAPI?method=getTopParticipantsData&api_key=cfrau&v=1.0&fr_id=1310&response_format=json';
var crewURL = 'http://perth.conquercancer.org.au/datasync/cfrau.top_ten_crew_Perth18.html'

$.getJSON(divisionsURL, function(data){
    
        var topDivisionsInfo = data.getTeamSearchByInfoResponse.team;

        // Sorting to make the highest number display descending.
        data.getTeamSearchByInfoResponse.team.sort(function(a, b){
            return a.amountRaised - b.amountRaised;  
        }).reverse();
        
        $(topDivisionsInfo).each(function (index, value){
            var averageAmount = value.amountRaised / value.numMembers;
            var floatAverage = parseFloat(averageAmount) / 100;
            var newFloatAve = floatAverage.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            
            var floatTotal = parseFloat(value.amountRaised) / 100;
            var fixedTotal = floatTotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            
            if (value.divisionName === null) {
                value.divisionName = "No Division Set";
        };
            

        switch (value.divisionName) {
            case "Community":
                $(".top-community").append('<li>' + '<a target="_blank" href="'+ value.teamPageURL + '">'+ value.name + '</a>' + '<p>$' + fixedTotal +'</p> </li>');
                break;
            case "Corporate":
                   $(".top-corporate").append('<li>' + '<a target="_blank" href="'+ value.teamPageURL + '">'+ value.name + '</a>' + '<p>$' + fixedTotal +'</p> </li>');            
                break;
        };
    });

});
     
$.getJSON(fundRaisingURL, function(data){
    
    console.log(data);
    
        var topFundraisingData = data.getTopParticipantsDataResponse.teamraiserData;
        
        $(topFundraisingData).each(function (index, value){
            $(".top-fundraising").append('<li>' + '<a>' + value.name + '</a>'  + '<p>' + value.total + '</p> </li>');
        });
    });

$.get(crewURL, function(data) {
    $(".top-crew").append(data)
    console.log(data)
})

</script>


