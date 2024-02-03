window.addEventListener('load', () => {

    const createProjectDelete = () => {
         // 削除ボタンに対するイベントリスナーを設定
        document.querySelectorAll('.create-delete-button').forEach(function(button) {
            button.addEventListener('click', function() {
                // 削除ボタンの親要素の「project-info-wrap」を取得
                var projectInfoWrap = button.closest('.project-info-wrap');
                // 親要素をDOMから削除
                if (projectInfoWrap) {
                    projectInfoWrap.remove();
                }
            });
        });
    }
    createProjectDelete();

    document.getElementById('addProject').addEventListener('click', () => {
        createProjectDelete();
    })
})
