var numofRounds=0;
var diceValue =[];
var points
var balance =0;
/* This function Rolls the Dice */
function rollandPlay(numDice){
    rollthedice(numDice);
    calculatePoints();
    avg_points();
}
function rollthedice(numDice){
    console.log(numDice);
    diceValue =[];
    for (var i = 1; i<=numDice; i++){
       var roll = Math.floor(Math.random() *6) + 1;
        diceValue.push(roll);
    }
    console.log('dice:' + diceValue);
    numofRounds += 1;
    document.getElementById("rounds").innerHTML = numofRounds; 
    var dice_img ='';
    for(i=0; i<diceValue.length; i++){
        dice_img +='<img title="&copy;www.freeiconspng.com/downloadimg/27649c" style ="height: 5%; width: 4%;" src="images/dice'+ diceValue[i] +'.png ">   </img>';
    }  
    
    document.getElementById("rolleddice").innerHTML = dice_img;
}
/* This function checks that All N dice have the same value */
function case1(){
    for (var i =1; i<diceValue.length; i++){
        if(diceValue[i] !== diceValue[i-1]) {
            return false;
        }
    }
return true;
}
/*This function checks that N - 1 but not N dice have the same value */
function case2(){
    missMatchCount = 0;
    for (var i = 0; i<diceValue.length; i++){
        for(var j =0; j<diceValue.length; j++){
            if(diceValue[i]!=diceValue[j]){
                missMatchCount++;
            }
        }
    }
    if(missMatchCount == 1){
        return true;
    }
    return false;
}

/* A run (a sequence K+1 to K+N for some K ≥ 0)	20 + Sum of the values of all the dice */
function case3(){
    var numofdifferences = 0;
    for (var i=0; i<diceValue.length; i++){
            if (Math.abs(diceValue[i+1] - diceValue[i]) == 1) {
                numofdifferences++;
            }
    }
    if (numofdifferences == 1){
        return true;
    }
    return false;
}

/* This function checks that All dice have different values, but it is not a run */
function case4(){
    counter = 0;
    for(var i=0; i<diceValue.length; i++){
        if (diceValue[i+1] != diceValue[i]){
            counter++;
        }
    }
    if(diceValue.length == counter){
        return true;
    }
return false;
}

/* Any other outcome	0 */


/* This function calculates the points based on different cases*/
function calculatePoints(){
    if(case1()){
        points = 60;
        for(i=0; i<diceValue.length; i++){
            points += diceValue[i];
        }
        balance+=points;
        document.getElementById("balance").innerHTML =balance;
    }
    else if(case2()){
        points = 40;
        for(i=0; i<diceValue.length; i++){
            points += diceValue[i];
        }
        balance+=points;
        document.getElementById("balance").innerHTML =balance;
    }
    else if(case3()){
        points = 20;
        for(i=0; i<diceValue.length; i++){
            points += diceValue[i];
        }
        balance+=points;
        document.getElementById("balance").innerHTML =balance;
    }
    else if(case4()){
        points=0;
        for(i=0; i<diceValue.length; i++){
            points += diceValue[i];
        }

        balance+=points;
        document.getElementById("balance").innerHTML =balance;
    }
    else{
        points = 0;
        balance+=points;
        document.getElementById("balance").innerHTML =balance;
    }

}
function avg_points(){
    var average = (balance / numofRounds);
    if(average > 0) {
        console.log("avg:" + average);
        var avgPoints = average.toFixed(1);
        document.getElementById("avgpoints").innerHTML = avgPoints;
    }
}
/* This function validates the user input and runs the game*/
function validateAndPlay(numDice) {
    console.log("numDice:"+ numDice);
    var numDiceElement = document.getElementById("numDice");
    console.log("numdiceElement: " + numDiceElement);
    if(!numDiceElement.checkValidity()) {
        document.getElementById("errorMessage").innerHTML = 'Enter number between 3 and 6';
    } else {
        document.getElementById("errorMessage").innerHTML = ''
        console.log("No errors");    
        rollandPlay(numDice);
       
    }   
}


