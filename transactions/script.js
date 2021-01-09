$(".sidenav").sidenav();
$("input.autocomplete").autocomplete({
  data: {
    Apple: `https://randomuser.me/api/portraits/men/${Math.floor(
      Math.random() * 100
    )}.jpg`,
    Microsoft: `https://randomuser.me/api/portraits/men/${Math.floor(
      Math.random() * 100
    )}.jpg`,
    Google: `https://randomuser.me/api/portraits/men/${Math.floor(
      Math.random() * 100
    )}.jpg`
  }
});