
if('serviceWorker' in navigator){
    navigator.serviceWorker
    .register('../swcache.js')
    .then(console.log)
    .catch(console.log);
}
