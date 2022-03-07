window.onload = function(){        
    function clear(e){    
        e.preventDefault();                
        document.forms[0].reset();
        document.getElementById("statusCode").value = '';
        document.getElementById("responseBody").value = '';
    }; 
    const form = document.getElementById('apiform');
    form.addEventListener('submit', sendRequest);
    document.getElementById("clear").addEventListener('click', clear);

    function sendRequest(e) {
        e.preventDefault();
        var form = document.querySelector('form');
        var data = new FormData(form);
        function search(str) {
            return data.get(str);   
        }

        function handleAjax() {
            var request = new XMLHttpRequest();
            request.open(search('method'), search('resourceUrl'), true);
            request.setRequestHeader('Content-type', 'application/json');
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {                
                    document.getElementById('responseBody').value = JSON.stringify(JSON.parse(request.responseText));
                    document.getElementById('statusCode').value = JSON.stringify(request.status);
                }
            }
            request.send(search('requestBody'));
        }

        handleAjax();
    };
};
 
