window.addEventListener('DOMContentLoaded', () => {

    const modalActive = () => {
        const modal = document.querySelector('.clientModal');
        const clientElem = document.querySelectorAll('.clientElem');
        const closeElem = document.querySelectorAll('.closeElem');
        const setShiftIdElem = document.querySelector('.setShiftIdElem');

        // 新規か既存か選択の挙動制御
        const actionAreaActive = () => {
            const radio = document.querySelectorAll('.witchRadio');
            const actionElem = document.querySelectorAll('.actionElem');

            for(let i = 0; i < radio.length; i++){
                radio[i].addEventListener('change', () => {
                    for(let j = 0; j < actionElem.length; j++){
                        actionElem[j].style.display = 'none';
                    }
                    actionElem[i].style.display = 'flex';
                })
            }
        }

        for(let i = 0; i < clientElem.length; i++){
            clientElem[i].addEventListener('click', () => {
                // モーダル表示
                modal.style.display = 'block';
                actionAreaActive();

                setShiftIdElem.value = clientElem[i].querySelector('.input').value;
            })
        }

        // モーダル閉じる
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }
    }
    modalActive();
})
