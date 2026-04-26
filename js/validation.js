function validateForm() {
    let amount = document.forms["form"]["amount"].value;
    if (amount == "") {
        alert("Amount must be filled out");
        return false;
    }
return true;
}

function validateExpense(){

let amount=document.getElementById("amount").value;

if(amount=="" || amount<=0){

alert("Please enter valid expense amount");
return false;

}

return true;
}