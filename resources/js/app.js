import './bootstrap';

function resizeIframe(obj){
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 10 + 'px';
}
window.resizeIframe = resizeIframe
