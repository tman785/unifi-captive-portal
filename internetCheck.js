/* 
 * Script to be used to check access to the internet.  
 * You can change the redirect to any internet page you want. 
 * It's not clear how Apple/Android actually checks for internet access.  But this works
 *
 */ 

function checkAndRedirect() {
      const urlToCheck = 'http://captive.apple.com';

      fetch(urlToCheck, { method: 'HEAD' })
        .then(response => {
          if (response.ok) {
            window.location.href = 'https://www.apple.com';
          } else {
            console.log('Webpage is not up.');
            // You can add additional actions or error handling here if needed
          }
        })
        .catch(error => {
          console.error('Error checking webpage:', error);
          // You can add additional error handling here if needed
        });
    }

    