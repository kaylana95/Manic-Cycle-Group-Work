function editBikeInfo(){
    document.getElementById("component").disabled = false;
    document.getElementById("make").disabled = false;
    document.getElementById("model").disabled = false;
    document.getElementById("comments").disabled = false;
    document.getElementById("editBikeBtn").disabled = true;
    document.getElementById("saveBikeBtn").hidden = false;
    document.getElementById("editBikeBtn").hidden = true;
}

function logoutBtn() {
    var txt;
    var r = confirm("Are you sure that you want to terminate the session?");
    
    if (r == true) {
        window.location.href="logoutController.php";
    } 
}

function editFrameInfo(){
    document.getElementById("hardTailOrDualSus").disabled = false;
    document.getElementById("frameMake").disabled = false;
    document.getElementById("frameModel").disabled = false;
    document.getElementById("frameComments").disabled = false;
    document.getElementById("editFrameInfoBtn").disabled = true;
    document.getElementById("saveFrameInfoBtn").hidden = false;
    document.getElementById("editFrameInfoBtn").hidden = true;
}