/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*function isDOMLoaded()
{
 return document.readyState == 'complete' || document.readyState == 'ready';
}*/
aftload();
var acc=document.getElementsByClassName('accordion'),close=document.querySelectorAll('.close');;
var i;
for (i = 0; i < acc.length; i++) 
{
    acc[i].addEventListener('click',accordion);
}
if(document.getElementById('register-button'))
{
    document.getElementById('register-button').addEventListener('click',function(){
        document.getElementById('reglog-modal').style.display='block';
        document.getElementById('register').style.display='block';
    });
    document.getElementById('register-span').addEventListener('click',function(){
        document.getElementById('register').style.display='none';
        document.getElementById('login').style.display='block';
    });
}
if(document.getElementById('login-button'))
{
    document.getElementById('login-button').addEventListener('click',function(){
        document.getElementById('reglog-modal').style.display='block';
        document.getElementById('login').style.display='block';
    });
    document.getElementById('login-span').addEventListener('click',function(){
        document.getElementById('login').style.display='none';
        document.getElementById('register').style.display='block';
    });
}
window.onclick = function(event) 
{
    if (event.target === document.getElementById('reglog-modal')) 
    {
        hide();
    }
};
for(i=0;i<close.length;i++)
{
    close[i].addEventListener('click',hide);
}
if(document.getElementById('errors'))
{
    document.querySelector('.closeerr').addEventListener('click',function(){
        document.getElementById('errors').style.display='none';
    });
}
if(document.getElementById('success'))
{
    document.querySelector('.closesc').addEventListener('click',function(){
        document.getElementById('success').style.display='none';
    });
}
document.getElementById('search-input').addEventListener('focus',function(){
    document.getElementById('suggestions').style.display='block';
});
document.getElementById('search-input').addEventListener('focusout',hidesug);


function hide()
{
    document.getElementById('reglog-modal').style.display = "none";
    document.getElementById('register').style.display='none';
    document.getElementById('login').style.display='none';
}
function accordion()
{
    this.classList.toggle('active');
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight)
    {
        panel.style.maxHeight = null;
        panel.style.borderBottom=null;
    } 
    else 
    {
        panel.style.maxHeight = panel.scrollHeight + 'px';
        panel.style.borderBottom='1px solid white';
    } 
}
function sleep(ms) 
{
    return new Promise(resolve => setTimeout(resolve, ms));
}
async function aftload()
{
    await sleep(1000);
    document.getElementById('cart-btn').classList.add('aftload');
    if(document.getElementById('lmbtn'))
    {
        document.getElementById('lmbtn').classList.add('aftload');
    }
}
async function hidesug()
{
    await sleep(100);
    document.getElementById('suggestions').style.display='none';
}