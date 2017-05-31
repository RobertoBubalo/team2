brojObjekata=0;
polje=[];

var generalX=0;
var privateX=0;
var workX=0;
var shoppingX=0;
var archiveX=0;
var checkedX=0;

loadIzLS();

//stvaranje objekta
function zadatak(rb,text,datum,odabir){
    this.redniBroj=rb+1;
    this.opis=text;
    this.dueDate=datum;  
    this.stanje=false;
    this.zaArhivu=false;
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
        ispis(tmpObjekt);
        polje[brojObjekata]=tmpObjekt;
        brojObjekata++;
    
        spremiULS(tmpObjekt);
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
    $( "#inputDatum" ).datepicker();
    $(".inputDatumi").datepicker({
        onClose: function(dateText,inst){
           spremiNoviDatum(this); 
        } 
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
    
       brojacTodo(polje[brojObjekata].mapa, polje[brojObjekata].zaArhivu);
    if(polje[brojObjekata].mapa=="General"&&polje[brojObjekata].zaArhivu==false){
        ispis(polje[brojObjekata]);
    }
       
       brojObjekata++
   }
}











function removeZadatak(sender){
    var idZaRemove=sender.parentNode;
    var idic=idZaRemove.getAttribute('id');
    document.getElementById(idZaRemove.getAttribute('id')).style.display='none';
    polje[idic].zaArhivu=true;
    
    localStorage.setItem(idic, JSON.stringify(polje[idic]));
    
  
}

function stanje(sender){
    var idParentElementa=sender.parentNode;
    var idDiv=idParentElementa.getAttribute('id');
  
//CHECKBOX STYLE IZMJENA OVISNO O STANJU
    if(polje[idDiv].stanje==false){
        polje[idDiv].stanje=true;
        document.getElementById(idDiv).style.display= 'none';
    }else{
        polje[idDiv].stanje=false;
        //document.getElementById(idDiv).style.display='unset';
    }
    
    
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
}



function ispisGeneral(){
    $( ".todoes" ).remove();
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="General"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
      
}





function ispisPrivate(){
    $( ".todoes" ).remove();
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="Private"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
    
    
}


function ispisWork(){
    $( ".todoes" ).remove();
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="Work"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
    
    
}


function ispisShopping(){
    $( ".todoes" ).remove();
    
    for(var i=0;i<brojObjekata;i++){
        if(polje[i].mapa=="Shopping"&&polje[i].zaArhivu==false){
            ispis(polje[i]);
        }
    }
    
    
}

//jquerry datum dropdown
$( function() {
    $( "#inputDatum" ).datepicker();
    $(".inputDatumi").datepicker({
        onClose: function(dateText,inst){
           spremiNoviDatum(this); 
        } 
    });
  } );

document.getElementById('input').addEventListener("keydown",function (g){
        if (g.keyCode===13){
            noviObjekt();
        }
    });

function brojacTodo(mapica,arhiva){
    if(mapica=="General"&& arhiva==false){
        generalX++;
        console.log(generalX);
    }
}
