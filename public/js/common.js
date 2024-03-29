window.addEventListener('load', () => {

    const toggleNav = () => {
        const main = document.querySelector('.main');
        const nav = document.querySelector('.nav');
        const logo = document.getElementById('logoImg');
        const navToggle = document.getElementById('navToggle');
        const navItems = document.querySelectorAll('.nav-item');
        const navTexts = document.querySelectorAll('.navTxt');

        // ナビゲーションの開閉状態をlocalStorageから取得
        const isNavOpen = localStorage.getItem('isNavOpen') === 'true';

        // 初期表示時に状態を適用
        const applyNavState = (isOpen) => {
            navToggle.classList.toggle('open', isOpen);
            navTexts.forEach(text => text.style.display = isOpen ? 'block' : 'none');
            navItems.forEach(item => item.classList.toggle('close-nav-item', !isOpen));
            nav.classList.toggle('nav-close', !isOpen);
            logo.classList.toggle('nav-img-close', !isOpen);
            main.classList.toggle('main-change-width', !isOpen);
        };

        applyNavState(isNavOpen);

        navToggle.addEventListener('click', () => {
            const isOpen = navToggle.classList.contains('open');
            applyNavState(!isOpen);
            // 状態をlocalStorageに保存
            localStorage.setItem('isNavOpen', !isOpen);
        });
    };

    toggleNav();
})
