window.addEventListener('DOMContentLoaded', () => {

    const calendarClmActive = () => {
        const checkBox = document.querySelectorAll('.viewClmCheck');

        for(let i = 0; i < checkBox.length; i++){
            checkBox[i].addEventListener('change', () => {
                const className = checkBox[i].value;
                const targetElem = document.querySelectorAll(`.${className}`);

                if(checkBox[i].checked){
                    for(let j = 0; j < targetElem.length; j++){
                        targetElem[j].style.display = 'flex';
                    }
                }else{
                    for(let j = 0; j < targetElem.length; j++){
                        targetElem[j].style.display = 'none';
                    }
                }
            })
        }
    }
    calendarClmActive();
})
