function myFunction(numer,id) {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById(id);
    filter = input.value.toUpperCase();
   
    ul = document.getElementById("myTable");
    li = ul.getElementsByTagName("tr");
    for (i = 1; i < li.length; i++) {
        a = li[i].getElementsByTagName("td")[numer];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }    
}

function showDate(){
  var input, filter, ul, li, a, i, txtValue;
    var input1 = document.getElementById("data1");
    var input2 = document.getElementById("data2");
   
    ul = document.getElementById("myTable");
    li = ul.getElementsByTagName("tr");
    for (i = 1; i < li.length; i++) {
        a = li[i].getElementsByTagName("td")[3];
        txtValue = a.textContent || a.innerText;
        if (input1.value<txtValue && input2.value>txtValue) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }    
        
}