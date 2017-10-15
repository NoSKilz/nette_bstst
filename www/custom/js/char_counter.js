/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(document.getElementById('comment-form'))
{
    document.getElementById('com').addEventListener('input',count);
}
if(document.getElementById('order'))
{
    document.getElementById('info').addEventListener('input',count);
}
function count()
{
    var counter=document.getElementById('charNum');
    var max=counter.dataset.count;
    var valuee=this.value.length;
    if(parseInt(valuee)>=parseInt(max))
    {
        counter.textContent='Dosáhli jste maximálního počtu znakú.';
        var sub=this.value.substring(0,max);
        this.value=sub;
    }
    else
    {
        var rem=parseInt(max)-parseInt(valuee);
        counter.textContent='Zbývající počet znaků: '+rem;
    }
}