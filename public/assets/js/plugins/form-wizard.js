(function () {
    "use strict";
    /*---------------------------------------------------------------------
        Fieldset
    -----------------------------------------------------------------------*/
    
    let currentTab =0;
    const ActiveTab=(n)=>{
        if(n==0){
            if(document.getElementById("account")){
                document.getElementById("account").classList.add("active");
                document.getElementById("account").classList.remove("done");
            }
            if( document.getElementById("personal")){
                document.getElementById("personal").classList.remove("done");
                document.getElementById("personal").classList.remove("active");
            }
            
        }
        if(n==1){
            if(document.getElementById("account")){
                document.getElementById("account").classList.add("done");
            }
            if(document.getElementById("personal")){
                document.getElementById("personal").classList.add("active");
                document.getElementById("personal").classList.remove("done");
            }
            if(document.getElementById("payment")){
                document.getElementById("payment").classList.remove("active");
                document.getElementById("payment").classList.remove("done");
            }
            if(document.getElementById("confirm")){
                document.getElementById("confirm").classList.remove("done");
                document.getElementById("confirm").classList.remove("active");   
            }

        }
        if(n==2){
            if(document.getElementById("account")){
                document.getElementById("account").classList.add("done");
            }
            if(document.getElementById("personal")){
                document.getElementById("personal").classList.add("done");
            }
            if(document.getElementById("payment")){
                document.getElementById("payment").classList.add("active");
                document.getElementById("payment").classList.remove("done");
            }
            if(document.getElementById("confirm")){
                document.getElementById("confirm").classList.remove("done");
                document.getElementById("confirm").classList.remove("active");
            }
        }
        if(n==3){
            if(document.getElementById("account")){
                document.getElementById("account").classList.add("done");
            }
            if(document.getElementById("personal")){
              document.getElementById("personal").classList.add("done");
            }
            if(document.getElementById("payment")){
                 document.getElementById("payment").classList.add("done");
            }
            if(document.getElementById("confirm")){
                document.getElementById("confirm").classList.add("active");
                document.getElementById("confirm").classList.remove("done");
            }
        }
    } 
    const showTab=(n)=>{
        var x = document.getElementsByTagName("fieldset");
        x[n].style.display = "block";
        console.log(n);
        ActiveTab(n);
       
    }
    const nextBtnFunction= (n) => {
        var x = document.getElementsByTagName("fieldset");
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        showTab(currentTab);
    }
    
    const nextbtn= document.querySelectorAll('.next')
    Array.from(nextbtn, (nbtn) => {
    nbtn.addEventListener('click',function()
    {
        nextBtnFunction(1);
    })
});

// previousbutton

const prebtn= document.querySelectorAll('.previous')
    Array.from(prebtn, (pbtn) => {
    pbtn.addEventListener('click',function()
    {
        nextBtnFunction(-1);
    })
});
    
})()