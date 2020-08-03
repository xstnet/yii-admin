
function replayComment(commentId, commentTitle) {
    let item = $("#commentContent");
    // todo scroll screen to comment-textarea
    item[0].focus();

    // todo clear other @
    let replayTo = `[@${commentTitle}#${commentId}]\n`;
    item.val(replayTo + item.val());

    console.log(replayTo);
}

function beforeComment() {
    let nickname = $('#commentNickname').val();
    let email = $('#commentEmail').val();
    if (nickname.length >= 2) {
        localStorage.setItem('nickname', nickname);
    }
    if (email.length > 0) {
        localStorage.setItem('email', email);
    }
}

function setParams() {
    let nickname = localStorage.getItem('nickname');
    let email = localStorage.getItem('email');
    if (nickname != null) {
        $('#commentNickname').val(nickname);
    }
    if (email != null) {
        $('#commentEmail').val(email);
    }
}