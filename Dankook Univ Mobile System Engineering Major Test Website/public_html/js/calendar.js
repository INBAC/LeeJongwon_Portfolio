var today = new Date();
function onClickBtn(dir){

    var objYear = document.getElementById("year");
    var objMonth = document.getElementById("month");

    if(dir == "prev"){
        if(objMonth.value == 1 && objYear.value == objYear.options[0].value) return;
        if(objMonth.value == 1 && objYear.value == objYear.options[1].value){
            objYear.value--;
            objMonth.value = 12;

            changeOption();
            return;
        }
        objMonth.value--;
        changeOption();
    }
    if(dir == "next"){
        if(objMonth.value == 12){
            if(objYear.value == objYear.options[1].value) return;
            objYear.value++;
            objMonth.value = 1;
            changeOption();

            return ;
        }
        objMonth.value++;
        changeOption();
    }
}
function loadOptions(){
    var thisYear = today.getFullYear();
    var objYear = document.getElementById("year");
    var objMonth = document.getElementById("month");

    for(var i=0; i<2; i++, thisYear++){
        var yearOpt = document.createElement("option");
        yearOpt.text = thisYear + "년";
        yearOpt.value = thisYear;

        objYear.options.add(yearOpt);
    } //년도 옵션은 이번 년도와 다음 년도까지

    for(var i=1; i<13; i++){
        var monthOpt = document.createElement("option");
        monthOpt.text = i+"월";
        monthOpt.value = i;

        objMonth.options.add(monthOpt);
    }

}
function setOptionDefault(){
    var thisMonth = today.getMonth();
    var objSel = document.getElementById("month");

    for(var i=0; i<objSel.options.length; i++){
        if(objSel.options[i].value == thisMonth) objSel.options[i+1].selected = "selected";
    }
}
function changeOption(){
    var year = document.getElementById("year").value;
    var month = document.getElementById("month").value;

    today = new Date(year, month-1);

    buildCalendar();
}
function buildCalendar(){
    var thisMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    var lastDate = new Date(today.getFullYear(), today.getMonth()+1, 0);

    var tbCalendar = document.getElementById("calendar");

    while (tbCalendar.rows.length > 1){
        tbCalendar.deleteRow(tbCalendar.rows.length-1);
    }

    var row = null;
    row = tbCalendar.insertRow();

    var cnt = 0;

    for(var i = 0; i<thisMonth.getDay(); i++){
        cell = row.insertCell();
        cell.classList.add("listBody");
        cnt = cnt + 1;
    }

    for(var i=1; i<=lastDate.getDate(); i++){
        cell = row.insertCell();
        cell.classList.add("listBody"); //셀(td) 생성 후 class명 date 추가

        num = document.createElement("div");
        num.classList.add("listDay");
        num.innerHTML = i; // td 안에 숫자 부분 생성

        cell.appendChild(num);

        cnt = cnt + 1;

        if(cnt % 7 == 1){
            num.classList.add("font-red");
        }

        if(cnt % 7 ==0){
            num.classList.add("font-blue");
            row = calendar.insertRow();
        }
    }

    if(cnt%7 != 0){
        for(var i=0; i < 7-cnt%7; i++){
            cell = row.insertCell();
            cell.classList.add("listBody");
        }
    } // 마지막 줄 공백 처리
}

window.onload = function(){
    loadOptions();
    setOptionDefault();
    buildCalendar();
}
