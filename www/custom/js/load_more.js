/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(document.getElementById('lmbtn'))
{
    document.getElementById('lmbtn').addEventListener('click',function(){
        var offset=this.dataset.offset;
        if(window.XMLHttpRequest)
        {
            xhttp = new XMLHttpRequest();
        }
        else
        {
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                if(this.responseText)
                {
                    document.getElementById('new-container').innerHTML = document.getElementById('new-container').innerHTML+
                    this.responseText;
                    document.getElementById('lmbtn').setAttribute('data-offset',parseInt(offset)+8);
                }
                else
                {
                    document.getElementById('lmbtn').textContent="To je vše :(";
                }
            }
        };
        xhttp.open('POST', 'http://localhost/better_shop/resources/load_more.php', true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('offset='+offset);
    });
}