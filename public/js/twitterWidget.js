function TwitterClient(host, elementId) {

    const conn = new WebSocket('ws://'+ host);
    let connected = false;

    let twitterContainer = document.getElementById(elementId);
    twitterContainer.classList.add('twitter-container');

    let tweets = document.createElement('div');
    tweets.setAttribute('id', 'tweets');
    twitterContainer.appendChild(tweets);

    renderForm();

    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        if (!e.data) {
            return;
        }

        const data =  JSON.parse(e.data);
        console.log(data);

        if (data.error) {
            renderErrorBlock();
            return;
        } else {
            removeErrorBlock();
        }

        renderMessageBlock();
        renderTweets(data);

        connected = true;
    };

    conn.onerror = function (e) {
        const data =  JSON.parse(e.data);
        console.log('error');
        console.log(data);
    }

    function renderTweets(data) {
        let newTweets = document.createElement('div');
        newTweets.setAttribute('id', 'tweets');

        data.forEach((tweet) => {
            let tweetElement = renderTweet(tweet);
            newTweets.appendChild(tweetElement);
        })

        let tweets = document.getElementById('tweets');
        tweets.replaceWith(newTweets);
    }

    function renderTweet(tweet) {
        let element = document.createElement('div');
        element.classList.add('twitter-tweet');

        let authorLink = document.createElement('a');
        authorLink.href = 'https://twitter.com/' + tweet.user.name;
        authorLink.textContent = '@' + tweet.user.name

        let author = document.createElement('div');
        author.classList.add('twitter-author');
        author.innerText = 'Curated Tweets by: ';
        author.appendChild(authorLink);

        let text = document.createElement('div');
        text.classList.add('twitter-text');
        text.innerHTML = tweet.text;

        let createDate = new Date(tweet.created.date);
        let created = document.createElement('div');
        created.classList.add('twitter-created');
        created.innerHTML = createDate.toISOString().replace(/T/, ' ').substr(0, 19);

        let socialFavorites = document.createElement('span');
        socialFavorites.classList.add('twitter-social-favorites');
        socialFavorites.innerText = 'Favorites: ' + tweet.favorites;

        let socialRetweets = document.createElement('span');
        socialRetweets.classList.add('twitter-social-retweets');
        socialRetweets.innerText = 'Retweets: ' + tweet.retweets;

        let social = document.createElement('div');
        social.classList.add('twitter-social');
        social.appendChild(socialFavorites);
        social.appendChild(socialRetweets);

        element.appendChild(author);
        element.appendChild(text);
        element.appendChild(created);
        element.appendChild(social);

        return element;
    }

    function renderMessageBlock() {
        if (!connected) {
            return;
        }

        let infoElement = document.createElement('div');
        infoElement.classList.add('twitter-info');
        infoElement.innerHTML = 'Updating data ...';

        twitterContainer.insertBefore(infoElement, twitterContainer.firstChild);

        setTimeout(() => twitterContainer.removeChild(infoElement), 3000)
    }

    function renderErrorBlock() {
        let errorElement = document.createElement('div');
        errorElement.classList.add('twitter-error');
        errorElement.innerHTML = 'Try another user name';

        twitterContainer.insertBefore(errorElement, twitterContainer.firstChild);
    }

    function removeErrorBlock() {
        let errorElement = twitterContainer.querySelector('.twitter-error');
        errorElement && twitterContainer.removeChild(errorElement);
    }

    function renderForm() {
        let userInput = document.createElement('input');
        userInput.setAttribute('type', 'text');
        userInput.setAttribute('name', 'user');

        let submitInput = document.createElement('input');
        submitInput.setAttribute('type', 'submit');
        submitInput.setAttribute('value', 'Send');


        let form = document.createElement('form');
        form.setAttribute('name', 'twitterUser');
        form.appendChild(userInput)
        form.appendChild(submitInput)

        form.onsubmit = () => {
            let user = userInput.value;
            userInput.value = '';
            conn.send(user);
            return false;
        };

        twitterContainer.insertBefore(form, twitterContainer.firstChild);
    }

    return {}
}
