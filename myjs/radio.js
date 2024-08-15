function unsetRadio(radioGroupName) {
  const radios = document.getElementsByName(radioGroupName);
  radios.forEach((radio) => {
    radio.checked = false;
  });
}
