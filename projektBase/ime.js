brojObjekata=0;
clickCounterArhiva=0; clickCounterChecked=0;
polje=[];
loadIzLS();

//stvaranje objekta
function zadatak(rb,text,datum){
    this.redniBroj=rb+1;
    this.opis=text;
    this.dueDate=datum;  
    this.stanje=false;
    this.zaArhivu=false;
    
    this.izmjenaOpisa = function(tmp){
        this.opis=tmp;
    }
}

//uzima vrijednost iz unosa opisa i datuma i salje u funkciju ispis,dodaje u polje objekata, i sprema u LocalStorage
function noviObjekt(){
    var text= document.getElementById('input').value;
    document.getElementById('input').value="";
    if(text==""||text==null||text==undefined||text==" "){
        alert("Please, enter an JustDoIt");
        
    }else{
        var datum= document.getElementById('inputDatum').value;
          /*  if(datum==""||datum==null||datum==undefined||datum==" "){
                datum="Date not set";
            }*/
        document.getElementById('inputDatum').value="";
        var tmpObjekt= new zadatak(brojObjekata,text,datum);
        ispis(tmpObjekt);
        polje[brojObjekata]=tmpObjekt;
        brojObjekata++;
    
        spremiULS(tmpObjekt);
    }
}

document.getElementById('input').addEventListener("keydown",function (g){
        if (g.keyCode===13){
            noviObjekt();
        }
    });


function ispis(tmpObjekt){
   if(tmpObjekt.zaArhivu==false){
    
        var forma=document.createElement("div");//kreira <div>
            forma.setAttribute("id", brojObjekata); //<div id="">

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
        document.getElementById(brojObjekata).style.display= 'none';
        //document.getElementById(brojObjekata).style.text-decorit..
     }else{
        //document.getElementById(brojObjekata).style.display='unset';
     }
       
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
    
       ispis(polje[brojObjekata]);
    
       brojObjekata++
   }
}

function clearAll(){
    localStorage.clear();
    brojObjekata=0;
    localStorage.setItem("brojObjekata",brojObjekata);
}



function removeZadatak(sender){
    var idZaRemove=sender.parentNode;
    var idic=idZaRemove.getAttribute('id');
    document.getElementById(idZaRemove.getAttribute('id')).style.display='none';
    polje[idic].zaArhivu=true;
    
    localStorage.setItem(idic, JSON.stringify(polje[idic]));
    
    //NASTAVAK KAD BUDEMO RADILI ARHIVU, DA DOSLOVNO REMOVE-A IZ LS 
    //localStorage.removeItem(idic);
    //polje.splice(idic,1);
    //brojObjekata--;
    //localStorage.setItem("brojObjekata",brojObjekata);
    
    
    
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


//jquerry datum dropdown
$( function() {
    $( "#inputDatum" ).datepicker();
    $(".inputDatumi").datepicker({
        onClose: function(dateText,inst){
           spremiNoviDatum(this); 
        } 
    });
  } );


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

function otvoriArhivu(){
    
        document.getElementById("lista").style.display="none";
        document.getElementById("checked").style.display="none";
        document.getElementById("arhiva").style.display="unset";
        
        $(".arhiva").remove();
        
        for(var i=0;i<brojObjekata;i++){
            ispisArhive(polje[i]);
        }
    
    
}

function ispisArhive(tmpObjekt){
    
        if(tmpObjekt.zaArhivu==true&&tmpObjekt.opis!=""){

            var forma=document.createElement("div");
                forma.setAttribute("class", "arhiva"); 
                
                var opisAr=document.createElement("li");
                    opisAr.setAttribute("class","opisAr");
                    opisAr.appendChild(document.createTextNode(tmpObjekt.opis));
            
                var dueDateAr=document.createElement("li");
                    dueDateAr.setAttribute("class","dueDateAr");
                    dueDateAr.appendChild(document.createTextNode(tmpObjekt.dueDate));
            
                forma.appendChild(opisAr);
                forma.appendChild(dueDateAr);

                document.getElementById('arhiva').appendChild(forma);


       }
}

function otvoriChecked(){
    
        document.getElementById("lista").style.display="none";
        document.getElementById("arhiva").style.display="none";
        document.getElementById("checked").style.display="unset";
        
        $(".checked").remove();
        for(var i=0;i<brojObjekata;i++){
            ispisChecked(polje[i]);
        }
    
}

function ispisChecked(tmpObjekt){
    if(tmpObjekt.stanje==true&&tmpObjekt.opis!=""){

            var forma=document.createElement("div");
                forma.setAttribute("class", "checked"); 
                
                var opisCh=document.createElement("li");
                    opisCh.setAttribute("class","opisCh");
                    opisCh.appendChild(document.createTextNode(tmpObjekt.opis));
            
                var dueDateCh=document.createElement("li");
                    dueDateCh.setAttribute("class","dueDateCh");
                    dueDateCh.appendChild(document.createTextNode(tmpObjekt.dueDate));
            
                forma.appendChild(opisCh);
                forma.appendChild(dueDateCh);

                document.getElementById('checked').appendChild(forma);
    }
}

function otvoriGeneral(){
        document.getElementById("lista").style.display="unset";
        document.getElementById("arhiva").style.display="none";
        document.getElementById("checked").style.display="none";
        $(".checked").remove();
        $(".arhiva").remove();
}