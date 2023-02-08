import axios from "axios";
function onCheckQuestion(){
    let url=this.closest('a').href
    axios.post(url).catch((error)=>{
        alert(error.message)
    })
}
document.querySelectorAll(".questionsCheck").forEach((checkbox)=>{
    checkbox.addEventListener("click",onCheckQuestion)
})
