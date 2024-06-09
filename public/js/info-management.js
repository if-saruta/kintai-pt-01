window.addEventListener('load', () => {

    const indexPage = document.getElementById('index');
    const editPage = document.getElementById('edit');

    if(indexPage){
        const addCommma = () => {
            const numberTags = document.querySelectorAll('p.w-fee-input');

            // 各pタグのテキストを三桁区切りのカンマ形式に変換
            numberTags.forEach(p => {
                if (p.textContent.trim() !== '') {
                    const number = parseInt(p.textContent, 10);
                    if (!isNaN(number)) { // 数値であればフォーマットを適用
                        p.textContent = number.toLocaleString('en-US');
                    }
                }
            });
        }
        addCommma();
    }

    if(editPage){
        const formSubmit = () => {
            const form = document.getElementById('form');
            const button = document.getElementById('submit');

            button.addEventListener('click', () => {
                form.submit();
            })
        }
        formSubmit();

        // 事務手数料の料金の連動
        const amountLinkage = () => {
            const amountLinkage = document.querySelectorAll('.amountLinkage');

            amountLinkage.forEach(input => {
                input.addEventListener('change', () => {
                    const value = input.value;
                    amountLinkage.forEach(otherInput => {
                        if(otherInput !== input){
                            otherInput.value = value;
                        }
                    })
                })
            })
        }
        amountLinkage();

        const commmaActive = () => {
            const inputElem = document.querySelectorAll('.commaInput');
            for(let i = 0; i < inputElem.length; i++){
                inputElem[i].addEventListener('input', function(e) {
                    // 入力値からカンマを削除し、数値に変換
                    var value = e.target.value.replace(/,/g, '');
                    var numberValue = parseInt(value, 10);

                    // isNaN関数で数値かどうかをチェック
                    if (!isNaN(numberValue)) {
                        // 数値をロケールに応じた文字列に変換（例: "1,234"）
                        e.target.value = numberValue.toLocaleString();
                    } else {
                        // 数値でない場合は入力を空にする
                        e.target.value = '';
                    }
                })

                // 入力値からカンマを削除し、数値に変換
                var value = inputElem[i].value.replace(/,/g, '');
                var numberValue = parseInt(value, 10);

                // isNaN関数で数値かどうかをチェック
                if (!isNaN(numberValue)) {
                    // 数値をロケールに応じた文字列に変換（例: "1,234"）
                    inputElem[i].value = numberValue.toLocaleString();
                } else {
                    // 数値でない場合は入力を空にする
                    inputElem[i].value = '';
                }
            }
        }
        commmaActive();
    }
})
