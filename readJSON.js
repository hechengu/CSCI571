    //获取<header>和</section>的引用
    let header = document.querySelector('header');
    let section = document.querySelector('section');
 
    //保存一个json文件访问的URL作为一个变量
    let requestURL = 'json/superheroes.json';
    //创建一个HTTP请求对象
    let request = new XMLHttpRequest();
    //使用open（）打开一个新请求
    request.open('GET', requestURL);
    //设置XHR访问JSON格式数据，然后发送请求
    // request.responseType = 'json';
    //设置XHR访问text格式数据
    request.responseType = 'text';
    request.send();
 
    //处理来自服务器的数据
    request.onload = function() {
        let superHeroesText = request.response;
        let superHeroes = JSON.parse(superHeroesText);
        // let superHeroes = request.response;
        populateHeader(superHeroes);
        showHeroes(superHeroes);
    };
 
    //对header进行定位
    function populateHeader(jsonObj) {
        let myH1 = document.createElement('h1');
        myH1.textContent = jsonObj['squadName'];
        header.appendChild(myH1);
 
        let myPara = document.createElement('p');
        myPara.textContent = 'Hometown: ' + jsonObj['hometown'] + ' //Formed: ' + jsonObj['formed'];
        header.appendChild(myPara);
    }
 
    function showHeroes(jsonObj) {
        //用heroers存储json文件里menbers的信息
        let heroes = jsonObj['members'];
 
        for (let i = 0; i < heroes.length; i++) {
            //创建一系列页面元素
            let myArticle = document.createElement('article');
            let myH2 = document.createElement('h2');
            let myPara1 = document.createElement('p');
            let myPara2 = document.createElement('p');
            let myPara3 = document.createElement('p');
            let myList = document.createElement('ul');
 
            myH2.textContent = heroes[i].name;
            myPara1.textContent = 'Secret identity: ' + heroes[i].secretIdentity;
            myPara2.textContent = 'Age: ' + heroes[i].age;
            myPara3.textContent = 'Superpowers:';
 
            let superPowers = heroes[i].powers;
            for(let j = 0; j < superPowers.length; j++) {
                let listItem = document.createElement('li');
                listItem.textContent = superPowers[j];
                myList.appendChild(listItem);
            }
 
            myArticle.appendChild(myH2);
            myArticle.appendChild(myPara1);
            myArticle.appendChild(myPara2);
            myArticle.appendChild(myPara3);
            myArticle.appendChild(myList);
            section.appendChild(myArticle);
        }
