import axios from "axios";
//this code send a post request to change the "vu" attribute of the current question
function onCheckQuestion(){
    let url=this.closest('a').href
    axios.post(url).catch((error)=>{
        alert(error.message)
    })
}
document.querySelectorAll(".questionsCheck").forEach((checkbox)=>{
    checkbox.addEventListener("click",onCheckQuestion)
})
