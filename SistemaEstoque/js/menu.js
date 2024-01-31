const menuWidth = getComputedStyle(document.documentElement);
const divLogo = document.getElementById('div-logo');
const arrow = document.getElementById('arrow-menu');
const divProfile = document.getElementById('div-profile');

document.getElementById('arrow-menu').addEventListener('click', function() {

    if(menuWidth.getPropertyValue('--side-menu-width') == '18%'){
        document.documentElement.style.setProperty('--side-menu-width', '6%')
        divLogo.style.display = "none"
        arrow.style.transform = "rotate(180deg)"
        divProfile.classList.add('div-profile-active');
    }else{
        document.documentElement.style.setProperty('--side-menu-width', '18%')
        divLogo.style.display = "block"
        arrow.style.transform = "rotate(360deg)"
        divProfile.classList.remove('div-profile-active');
    }
    
    // Seleciona todos os elementos <span> com a classe 'span-menu'
    var spans = document.getElementsByClassName('span-menu');
    var sideMenu = document.getElementsByClassName('side-menu-itens');
    
    // Adiciona a classe desejada a cada elemento <span>
    for (var i = 0; i < spans.length; i++) {
        if (spans[i].classList.contains('menu-itens-active')) {
            // Se a classe estiver presente, remove-a
            spans[i].classList.remove('menu-itens-active');
            sideMenu[i].classList.remove('side-menu-itens-active');
        } else {
            // Se a classe não estiver presente, adiciona-a
            spans[i].classList.add('menu-itens-active');
            sideMenu[i].classList.add('side-menu-itens-active');
        }
    }
});
