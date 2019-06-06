//**********************************************************************************************************
// File Name: date.js
// Description: This little java script is used display the date in long form on a webpage. 
//      Ex: Thursday, January 03, 2002.  So if you just wanted to display the date on your
//      website you could easily do it with this .js file and one simple little line of code.
//      Where ever you want the date to be displayed at just add this code below:
//                   <script language="JavaScript" src="date.js"></script>
// Have any questions or requests? Just leave comments on Planet-Source-Code.com
//    OR e-mail me at: bass_tones@yahoo.com
//**********************************************************************************************************
// Set mydate to Date() which gets the value of the system clock.
var mydate=new Date()
var year=mydate.getYear()

// If the year is less than 1000 the add 1900 to the year for 2000 compliant
if (year < 1000)
   year+=1900

//Get the day, month, date from mydate.
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()

// If Day of month is less than 10 then use the format 01,02...09
if (daym<10)
   daym="0"+daym

//Arrays for the days of the week, and months of the year
var dayarray=new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado")
var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")

//Write out the date.
document.write("<b>"+dayarray[day]+",</b> "+daym+" de "+montharray[month]+" de "+year+"</font>")