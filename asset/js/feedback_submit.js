function check_input_rating() {
    const stars = document.querySelector('input[name="rating"]:checked');
    const comment = document.querySelector('textarea[name="comment"]');
    const respond = document.getElementById('respond');

    
    respond.innerHTML = '';
    const validPattern = /^[a-zA-Z0-9\s.,!?;:'"()\-\[\]{}]+$/;
    if (!stars) {
        respond.innerHTML = '<div class="alert alert-danger">Please input your rating</div>';
        return; 
    }

    if (comment.value.trim() === '') {
        respond.innerHTML = '<div class="alert alert-danger">Please input your comments</div>';
        return; 
    }else if (comment.value.length < 10 || comment.value.length > 255) {
        respond.innerHTML = '<div class="alert alert-danger">Comment must be between 10 and 255 characters</div>';
        return; 
    }
    
    if (!validPattern.test(comment.value.trim())) {
        respond.innerHTML = '<div class="alert alert-danger">Invalid characters in comment. Please use only letters, numbers, and basic punctuation.</div>';
        return;
    }


    document.getElementById('ratingForm').submit();
}
