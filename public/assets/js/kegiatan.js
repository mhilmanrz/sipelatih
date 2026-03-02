document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelector('.tab.active').classList.remove('active');
        tab.classList.add('active');
    });
});
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t => {
            t.classList.remove('bg-[#4cb7a5]')
            t.classList.add('bg-[#0c7c7c]')
        })
        tab.classList.remove('bg-[#0c7c7c]')
        tab.classList.add('bg-[#4cb7a5]')
    })
})