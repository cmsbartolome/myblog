function validateName(e){
    var t=document.getElementById(e),a=/[^a-zA-Z ]/gi;t.value.search(a)>-1&&(t.value=t.value.replace(a,""))
}

function validatePhoneNum(e){
    var t=document.getElementById(e),a=/[^0-9]/gi;t.value.search(a)>-1&&(t.value=t.value.replace(a,""))
}
