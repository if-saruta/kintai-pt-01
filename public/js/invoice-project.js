window.addEventListener('DOMContentLoaded', () => {

    const calendarClmActive = () => {
        const checkBox = document.querySelectorAll('.viewClmCheck');

        for(let i = 0; i < checkBox.length; i++){
            checkBox[i].addEventListener('change', () => {
                const className = checkBox[i].value;
                const targetElem = document.querySelectorAll(`.${className}`);
                if(checkBox[i].getAttribute('data-check') == 'one'){
                    if(checkBox[i].checked){
                        for(let j = 0; j < targetElem.length; j++){
                            targetElem[j].classList.remove('clm-none');
                        }
                    }else{
                        for(let j = 0; j < targetElem.length; j++){
                            targetElem[j].classList.add('clm-none');
                        }
                    }
                }else{
                    if(checkBox[i].checked){
                        for(let j = 0; j < targetElem.length; j++){
                            targetElem[j].classList.remove('all-clm-none');
                        }
                    }else{
                        for(let j = 0; j < targetElem.length; j++){
                            targetElem[j].classList.add('all-clm-none');
                        }
                    }
                }
                colspanCalc(checkBox[i]);
            })
        }

        const colspanCalc = (checkBox) => {

            var rightHead = document.querySelectorAll('.rightHead');
            var coHead = document.querySelectorAll('.co-head');

            const projectCountElem = document.getElementById('projectCount');
            const projectCount = projectCountElem.value;
            const clmHead = document.querySelectorAll('.clmHead');
            const companyCountElem = document.getElementById('companyCount');
            const companyCount = companyCountElem.value;
            const coClmHead = document.querySelectorAll('.coClmHead');
            let coCount = 0;
            let count = 0;

            if(projectCount > 1 || companyCount > 1){
                for(let i = 0; i < clmHead.length; i++){
                    if(!clmHead[i].classList.contains('clm-none') && !clmHead[i].classList.contains('all-clm-none')){
                        count++;
                    }
                }
                for(let i = 0; i < coClmHead.length; i++){
                    if(!coClmHead[i].classList.contains('all-clm-none')){
                        coCount++;
                    }
                }

                if(checkBox.checked){
                    for(let i = 0; i < rightHead.length; i++){
                        //  現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
                        var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
                        var newColspan = count / projectCount;
                        rightHead[i].setAttribute("colspan", newColspan.toString());
                        if(count != 0){
                            rightHead[i].classList.remove('clm-none');
                        }
                    }
                    for(let i = 0; i < coHead.length; i++){
                        var newColspan = coCount / companyCount;
                        coHead[i].setAttribute("colspan", newColspan.toString());
                        if(coCount != 0){
                            coHead[i].classList.remove('all-clm-none');
                        }
                    }
                }else{
                    for(let i = 0; i < rightHead.length; i++){
                        //  現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
                        var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
                        var newColspan = count / projectCount;
                        rightHead[i].setAttribute("colspan", newColspan.toString());
                        if(newColspan == 0){
                            rightHead[i].classList.add('clm-none');
                        }
                    }
                    for(let i = 0; i < coHead.length; i++){
                        var newColspan = coCount / companyCount;
                        coHead[i].setAttribute("colspan", newColspan.toString());
                        if(newColspan == 0){
                            coHead[i].classList.add('all-clm-none');
                        }
                    }
                }
            }

        }
    }
    calendarClmActive();
})
