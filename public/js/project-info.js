window.addEventListener('load', () => {

    const formSubmit = () => {
        const btn = document.getElementById('saveBtn');
        const form = document.getElementById('form');

        btn.addEventListener('click', () => {
            form.submit();
        })
    }
    formSubmit();

    // カンマの制御
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
        }
    }
    commmaActive();

    const profitCalc = () => {
        const reatailPrice = document.getElementById('retailPrice').value.replace(/,/g, '');
        const input = document.getElementById('hglRetail');
        const headInput = document.getElementById('headInput');
        const profitRate = document.getElementById('profitRate');

        input.addEventListener('change', () => {
            let inputRetail = input.value.replace(/,/g, '');
            let head = reatailPrice - inputRetail;
            let rate = Math.round((head / reatailPrice) * 100);

            headInput.value = head.toLocaleString();
            profitRate.value = rate + '%';
        })

    }
    profitCalc();
})
