
//delete today task, azurirati treba modal.. na remove zadatak

brojObjekata=0;
polje=[];

var generalX=0;
var privateX=0;
var workX=0;
var shoppingX=0;
var archiveX=0;
var checkedX=0;

var brojacTuM=0;

loadIzLS();

//stvaranje objekta
function zadatak(rb,text,datum,odabir){
    this.redniBroj=rb+1;
    this.opis=text;
    this.dueDate=datum;  
    this.stanje=false;
    this.zaArhivu=false;
    this.izbrisano=false;
    this.mapa=odabir;
    
    
}

//uzima vrijednost iz unosa opisa i datuma i salje u funkciju ispis,dodaje u polje objekata, i sprema u LocalStorage
function noviObjekt(){
    var text= document.getElementById('input').value;
    document.getElementById('input').value="";
    if(text==""||text==null||text==undefined||text==" "){
        alert("Please, enter an JustDoIt");
        
    }else{
        var datum= document.getElementById('inputDatum').value;
        document.getElementById('inputDatum').value="";
        
        //getanje izbora za upis u mapu
        var izbor=document.getElementsByClassName("filter-option pull-left");
        for (var i = 0; i < izbor.length; i++) {
        var odabir = izbor[i].innerText;
  
}
        
        var tmpObjekt= new zadatak(brojObjekata,text,datum,odabir);
        
        
        if(odabir=="General"){ispisGeneral();}
        else if(odabir=="Private"){ispisPrivate();}
        else if(odabir=="Work"){ispisWork();}
        else if(odabir=="Shopping"){ispisShopping();}
        ispis(tmpObjekt);
        
        polje[brojObjekata]=tmpObjekt;
        
        brojacTodo(polje[brojObjekata].mapa, polje[brojObjekata].zaArhivu, polje[brojObjekata].stanje,polje[brojObjekata].izbrisano);
        ispisBrojaca();
        
        brojObjekata++;
    
        spremiULS(tmpObjekt);
        ispisModala();
    }
}

function ispis(tmpObjekt){
    
   
    
    var forma=document.createElement("div");//kreira <div>
        forma.setAttribute("id", tmpObjekt.redniBroj-1); //<div id="">
        forma.setAttribute("class", "todoes");     

    var noviElement = document.createElement('input'); 
    //var textZaIspis=document.createTextNode(tmpObjekt.opis); //u str
    noviElement.setAttribute("class","opisi");
    noviElement.setAttribute("value",tmpObjekt.opis);

       //ISTRAZITI MALO - TRIGGER ZA ENTER // ne radi sa posebnom funkcijom osim inline ovako ... 
    noviElement.addEventListener("keydown",function (g){
        if (g.keyCode===13){
            izmjenaVrijednosti(this);
        }
                                });
    
    noviElement.setAttribute("type", "text" );
    
    
       
       
    var noviIks= document.createElement('button');
    noviIks.setAttribute("class","iksic");
    noviIks.setAttribute("onclick", "removeZadatak(this)"); //izvrsava funkciju ->kao parametar salje sebe kao butun u funkciju
    
    var x="×";
    textZaIspis=document.createTextNode(x);
    noviIks.appendChild(textZaIspis);
    
    //dinamicki checkbox u formu dodajemo(zadatak)
    var noviCheck=document.createElement('input');
    
       if(tmpObjekt.stanje==true){
        noviCheck.setAttribute("checked","true")
     }
    noviCheck.setAttribute("onclick", "stanje(this)");
    noviCheck.setAttribute("type","checkbox");
    
       
    forma.appendChild(noviElement);
    forma.appendChild(ispisDatum(tmpObjekt));
    forma.appendChild(noviIks);
       forma.appendChild(noviCheck);
    
    document.getElementById('lista').appendChild(forma);
    
       //checkbox stanje, opacity mjenja ili striketrought bla bla 
     if(tmpObjekt.stanje==true){
        document.getElementById(tmpObjekt.redniBroj-1).style.display= 'none';
         }
    
    
    $( function() {
    $( "#inputDatum" ).datepicker({dateFormat: 'dd/mm/yy'});
    $(".inputDatumi").datepicker({
        onClose: function(dateText,inst){
           spremiNoviDatum(this); 
        },
        dateFormat: 'dd/mm/yy'
        
    });
  } );   
    

    
}
    
function spremiULS(tmpObjekt){
    var str=JSON.stringify(tmpObjekt);
    localStorage.setItem(tmpObjekt.redniBroj-1, str);
    localStorage.setItem("brojObjekata",brojObjekata);
}

function loadIzLS(){
   for(var i=0;i<JSON.parse(localStorage.getItem("brojObjekata"));i++) {
       polje[i]=JSON.parse(localStorage.getItem(brojObjekata));
    
       brojacTodo(polje[brojObjekata].mapa, polje[brojObjekata].zaArhivu, polje[brojObjekata].stanje,polje[brojObjekata].izbrisano);
   
       
       if(polje[brojObjekata].mapa=="General"&&polje[brojObjekata].zaArhivu==false){
        ispis(polje[brojObjekata]);
    }
       
       brojObjekata++
   }
    ispisModala();
    ispisBrojaca();
}

function removeZadatak(sender){
    var idZaRemove=sender.parentNode;
    var idic=idZaRemove.getAttribute('id');
    document.getElementById(idZaRemove.getAttribute('id')).style.display='none';
    polje[idic].zaArhivu=true;
    
    if(polje[idic].mapa=="General"&&polje[idic].stanje==false){generalX--;}
    if(polje[idic].mapa=="Private"&&polje[idic].stanje==false){privateX--;}
    if(polje[idic].mapa=="Work"&&polje[idic].stanje==false){workX--;}
    if(polje[idic].mapa=="Shopping"&&polje[idic].stanje==false){shoppingX--;} archiveX++;
    if(polje[idic].stanje==true){checkedX--;}
    ispisBrojaca();
    
    localStorage.setItem(idic, JSON.stringify(polje[idic]));
    
    
    
  
}

function stanje(sender){
    var idParentElementa=sender.parentNode;
    var idDiv=idParentElementa.getAttribute('id');
  
//CHECKBOX STYLE IZMJENA OVISNO O STANJU
    if(polje[idDiv].stanje==false){
        polje[idDiv].stanje=true;
        document.getElementById(idDiv).style.display= 'none';
        
        if(polje[idDiv].mapa=="General"){generalX--;}
        if(polje[idDiv].mapa=="Private"){privateX--;}
        if(polje[idDiv].mapa=="Work"){workX--;}
        if(polje[idDiv].mapa=="Shopping"){shoppingX--;} checkedX++;
    
        ispisBrojaca();
        
        
        
        
    }else{
        polje[idDiv].stanje=false;
        document.getElementById(idDiv).style.display='none';
        if(polje[idDiv].mapa=="General"){generalX++;}
        if(polje[idDiv].mapa=="Private"){privateX++;}
        if(polje[idDiv].mapa=="Work"){workX++;}
        if(polje[idDiv].mapa=="Shopping"){shoppingX++;} checkedX--;
    
        ispisBrojaca();
    }
    
    ispisModala();
    var str=JSON.stringify(polje[idDiv]);
    localStorage.setItem(idDiv, str);
    
}

function izmjenaVrijednosti(sender){ //sprema se vrijednost u polje i u localstorage triggerom na enter
   
    var idParentElementa =sender.parentNode;
    var idDiv=idParentElementa.getAttribute('id');
    polje[idDiv].opis=sender.value;
    localStorage.setItem(idDiv, JSON.stringify(polje[idDiv]));
    
}

function ispisDatum(tmpObjekt){
    var inputDatum=document.createElement('input');
        inputDatum.setAttribute("type", "text");
        inputDatum.setAttribute("class", "inputDatumi");
        inputDatum.setAttribute("value",tmpObjekt.dueDate);
    return inputDatum;
}

function spremiNoviDatum(sender){
    var idParentElemeneta=sender.parentNode;
    var idDiv=idParentElemeneta.getAttribute('id');
    polje[idDiv].dueDate=sender.value;
    localStorage.setItem(idDiv, JSON.stringify(polje[idDiv]));
    ispisModala();
}

function ispisGeneral(){
    $( ".todoes" ).remove();
    activeDefColor();
    document.getElementById('activeG').style.color="#16B7FF;";
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="General"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
      
}

function ispisPrivate(){
    $( ".todoes" ).remove();
    activeDefColor();
    document.getElementById('activeP').style.color="#16B7FF";
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="Private"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
    
    
}

function ispisWork(){
    $( ".todoes" ).remove();
    activeDefColor();
    document.getElementById('activeW').style.color="#16B7FF";
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="Work"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
    
    
}

function ispisShopping(){
    $( ".todoes" ).remove();
    activeDefColor();
    document.getElementById('activeS').style.color="#16B7FF";
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="Shopping"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
    
    
}

function ispisArhiva(){
    $( ".todoes" ).remove();
    activeDefColor();
    document.getElementById('activeA').style.color="#16B7FF";
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].zaArhivu==true&&polje[i].izbrisano==false){
            prikazArhive(polje[i]);
        }
    }
    
    
}

function ispisChecked(){
    $( ".todoes" ).remove();
    activeDefColor();
    document.getElementById('activeC').style.color="#16B7FF";
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].stanje==true&&polje[i].zaArhivu==false){
            ispis(polje[i]);
            document.getElementById(i).style.display="inline-flex";
            document.getElementById(i).style.opacity=0.5;
        }
    }
    
    
}

function brojacTodo(mapica,arhiva,chekirano,izbrisano){
    if(mapica=="General"&& arhiva==false&& chekirano==false){
        generalX++;
    }else if(mapica=="Private"&& arhiva==false&& chekirano==false){
        privateX++;
    }else if(mapica=="Work"&& arhiva==false&& chekirano==false){
        workX++;
    }else if(mapica=="Shopping"&& arhiva==false&& chekirano==false){
        shoppingX++;
    }else if(((arhiva==true&& chekirano==false)||(arhiva==true&&chekirano==true))&&izbrisano==false){
        archiveX++;
    }else if(arhiva==false&& chekirano==true){
        checkedX++;
    }
    
}

function ispisBrojaca(){
    document.getElementById('general').innerHTML= generalX;
    document.getElementById('private').innerHTML=privateX;
    document.getElementById('work').innerHTML=workX;
    document.getElementById('shopping').innerHTML=shoppingX;
    document.getElementById('archive').innerHTML=archiveX;
    document.getElementById('checked').innerHTML=checkedX;
    
}

function activeDefColor(){
    document.getElementById('activeG').style.color="black";
    document.getElementById('activeP').style.color="black";
    document.getElementById('activeW').style.color="black";
    document.getElementById('activeS').style.color="black";
    document.getElementById('activeA').style.color="black";
    document.getElementById('activeC').style.color="black";
}

function prikazArhive(tmpObjekt){
    var forma=document.createElement("div");//kreira <div>
        forma.setAttribute("id", tmpObjekt.redniBroj-1); //<div id="">
        forma.setAttribute("class", "todoes");     

    var noviElement = document.createElement('input'); 
    
    noviElement.setAttribute("class","opisi");
    noviElement.setAttribute("value",tmpObjekt.opis);

    noviElement.addEventListener("keydown",function (g){
        if (g.keyCode===13){
            izmjenaVrijednosti(this);
        }
                                });
    
    noviElement.setAttribute("type", "text" );
    forma.appendChild(noviElement);
    forma.appendChild(ispisDatum(tmpObjekt));
   
    document.getElementById('lista').appendChild(forma);       
    
    $( function() {
    $( "#inputDatum" ).datepicker();
    $(".inputDatumi").datepicker({
        onClose: function(dateText,inst){
           spremiNoviDatum(this);
            ispisModala();
        } 
    });
  } );
}

function ispisModala(){
    
    $( ".tModal" ).remove();
    brojacTuM=0;
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].stanje==false&&polje[i].zaArhivu==false &&polje[i].dueDate==todayDate()){
            ispisTodoUModalu(polje[i]);
            brojacTuM++;
            
        }    
           
    }
    document.getElementById('brOb').setAttribute("data-badge",brojacTuM);
    todayDate();
    
}

function ispisTodoUModalu(tmpObjekt){
    
    var forma=document.createElement("div");//kreira <div>
        forma.setAttribute("class", "tModal");     

    var noviElement = document.createElement('li'); 
    
    noviElement.setAttribute("class","opisi");
            
            var strZaLi=document.createTextNode(tmpObjekt.opis);
    noviElement.appendChild(strZaLi);
    
    noviElement.setAttribute("type", "text" );
    
    
    
    forma.appendChild(noviElement);
    
    document.getElementById('ulToday').appendChild(forma);       
    
}

function todayDate(){

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var today = dd+'/'+mm+'/'+yyyy;
    
    document.getElementById('myModalLabel').innerHTML="Today tasks on "+today;
    return today;
}

function clearLS(){
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].zaArhivu==true&&polje[i].izbrisano==false){
            polje[i].izbrisano=true;
            archiveX--;
            spremiULS(polje[i]);
        }
    ispisArhiva();
    ispisBrojaca();
    }
}

function printAll(){
    var content = "<html>";
    content+="<b><center>Today tasks on "+todayDate()+"</b></center>";
        content += "<br/>"+"<b>General</b>" + "<br/>";
            content=content.replace("undefined", "");
            content+=contentGeneral();
        content += "<br/>"+"<b>Private</b>"+"<br/>";
            content=content.replace("undefined", "");
            content+=contentPrivate();
        content += "<br/>"+"<b>Work</b>" +"<br/>";
            content=content.replace("undefined", "");
            content+=contentWork();
        content +="<br/>"+ "<b>Shopping</b>"+ "<br/>";
            content=content.replace("undefined", "");
            content+=contentShopping();
            content=content.replace("undefined", "");
    content += "</body>";
    content += "</html>";

    var printWin = window.open('','','left=0,top=0,width=552,height=477,toolbar=0,scrollbars=0,status =0');
    printWin.document.write(content);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
}

function contentGeneral(){
    var content;
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].stanje==false&&polje[i].zaArhivu==false&&polje[i].mapa=="General"){
            content+=polje[i].opis+ "   " ;
            if(polje[i].dueDate!=undefined){
                content+=polje[i].dueDate+ "   " + "<br/>";
            }
        }
    }
    
    return content;
}
function contentPrivate(){
    var content;
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].stanje==false&&polje[i].zaArhivu==false&&polje[i].mapa=="Private"){
            content+=polje[i].opis+ "   " ;
            if(polje[i].dueDate!=undefined){
                content+=polje[i].dueDate+ "   " + "<br/>";
            }
        }
    }
    
    return content;
}
function contentWork(){
    var content;
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].stanje==false&&polje[i].zaArhivu==false&&polje[i].mapa=="Work"){
            content+=polje[i].opis+ "   " ;
            if(polje[i].dueDate!=undefined){
                content+=polje[i].dueDate+ "   " + "<br/>";
            }
        }
    }
    
    return content;
}
function contentShopping(){
    var content;
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].stanje==false&&polje[i].zaArhivu==false&&polje[i].mapa=="Shopping"){
            content+=polje[i].opis+ "   " ;
            if(polje[i].dueDate!=undefined){
                content+=polje[i].dueDate+ "   " + "<br/>";
            }
        }
    }
    
    return content;
}



//jquerry datum dropdown
$( function() {
    $( "#inputDatum" ).datepicker({dateFormat: 'dd/mm/yy'});
    $(".inputDatumi").datepicker({
        onClose: function(dateText,inst){
           spremiNoviDatum(this); 
        },
        dateFormat: 'dd/mm/yy'
        
    });
  } );

document.getElementById('input').addEventListener("keydown",function (g){
        if (g.keyCode===13){
            noviObjekt();
        }
    });