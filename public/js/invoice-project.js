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
                // var rightHead = document.querySelectorAll('.rightHead');
                // var coHead = document.querySelectorAll('.co-head');

                // const companyCountElem = document.getElementById('companyCount');
                // const companyCount = companyCountElem.value;

                // let clmHead = document.querySelectorAll('.clmHead');
                // let count = 0;
                // for(let j = 0; j < clmHead.length; j++){
                //     if(!clmHead[j].classList.contains('clm-none')){
                //         count++;
                //     }
                // }
                // console.log(count);
            })
        }

        const colspanCalc = (checkBox) => {

            var rightHead = document.querySelectorAll('.rightHead');
            var coHead = document.querySelectorAll('.co-head');

            const projectCountElem = document.getElementById('projectCount');
            const projectCount = projectCountElem.value;
            const clmHead = document.querySelectorAll('.clmHead');
            let count = 0;
            for(let i = 0; i < clmHead.length; i++){
                if(!clmHead[i].classList.contains('clm-none') && !clmHead[i].classList.contains('all-clm-none')){
                    count++;
                }
            }
            const companyCountElem = document.getElementById('companyCount');
            const companyCount = companyCountElem.value;
            const coClmHead = document.querySelectorAll('.coClmHead');
            let coCount = 0;
            for(let i = 0; i < coClmHead.length; i++){
                if(!coClmHead[i].classList.contains('all-clm-none')){
                    coCount++;
                }
            }
            console.log(coCount);

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


            // if(checkBox.getAttribute('data-check') == 'one'){
            //     if(checkBox.checked){
            //         for(let i = 0; i < rightHead.length; i++){
            //             // 現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
            //             var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
            //             // colspanの値を計算
            //             var newColspan = currentColspan + (1 * companyCount);
            //             // 計算した新しい値でcolspanを更新
            //             rightHead[i].setAttribute("colspan", newColspan.toString());
            //             rightHead[i].classList.remove('clm-none');
            //         }
            //     }else{
            //         for(let i = 0; i < rightHead.length; i++){
            //             // 現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
            //             var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
            //             // colspanの値を計算
            //             var newColspan = currentColspan - (1 * companyCount);
            //             // 計算した新しい値でcolspanを更新
            //             rightHead[i].setAttribute("colspan", newColspan.toString());
            //             if(newColspan == 0){
            //                 rightHead[i].classList.add('clm-none');
            //             }
            //         }
            //     }
            // }else{
            //     if(checkBox.checked){
            //         for(let i = 0; i < rightHead.length; i++){
            //             // // 現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
            //             // var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
            //             // // colspanの値を計算
            //             // var newColspan = currentColspan + currentColspan;
            //             // // 計算した新しい値でcolspanを更新
            //             let count = 0;
            //             let clmHead = document.querySelectorAll('.clmHead');
            //             for(let j = 0; j < clmHead.length; j++){
            //                 if(!clmHead[j].classList.contains('clm-none') || !clmHead[j].classList.contains('all-clm-none')){
            //                     count++;
            //                 }
            //             }
            //             rightHead[i].setAttribute("colspan", newColspan.toString());
            //             rightHead[i].classList.remove('clm-none');
            //         }
            //         for(let i = 0; i < coHead.length; i++){
            //             var currentColspan = parseInt(coHead[i].getAttribute("colspan"));
            //             var newColspan = currentColspan + 1;
            //             coHead[i].setAttribute("colspan", newColspan.toString());
            //             coHead[i].classList.remove('clm-none');
            //         }
            //     }else{
            //         for(let i = 0; i < rightHead.length; i++){
            //             // 現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
            //             var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
            //             // colspanの値を計算
            //             var newColspan = currentColspan - (currentColspan / companyCount);
            //             // 計算した新しい値でcolspanを更新
            //             rightHead[i].setAttribute("colspan", newColspan.toString());
            //             if(newColspan == 0){
            //                 rightHead[i].classList.add('clm-none');
            //             }
            //         }
            //         for(let i = 0; i < coHead.length; i++){
            //             var currentColspan = parseInt(coHead[i].getAttribute("colspan"));
            //             var newColspan = currentColspan - 1;
            //             coHead[i].setAttribute("colspan", newColspan.toString());
            //             if(newColspan == 0){
            //                 coHead[i].classList.add('clm-none');
            //             }
            //         }
            //     }
            // }
        }
    }
    calendarClmActive();
})
