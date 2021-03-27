window.onload = function () {
  // описание кнопки открытия/закрытия подробностей элементов чек листа

  const openAdditionButtons = document.querySelectorAll(
    ".main-block-check-list-additional_button"
  );

  for (let i = 0; i < openAdditionButtons.length; i++) {
    openAdditionButtons[i].onclick = function () {
      itemId = this.getAttribute("data-id");
      const additionBlock = document.querySelector(
        '.main-block-check-list-item-additional[data-id="' + itemId + '"]'
      );
      if (additionBlock.style.display !== "block") {
        additionBlock.style.display = "block";
      } else {
        additionBlock.style.display = "none";
      }
    };
  }

  // описание кнопки редактирования подробностей элементов чек листа

  const changeAdditionButtons = document.querySelectorAll(
    ".list-item-additional-change"
  );

  for (let i = 0; i < changeAdditionButtons.length; i++) {
    changeAdditionButtons[i].onclick = function () {
      itemId = this.getAttribute("data-id");
      this.style.display = "none";
      const textAdditionInput = document.querySelector(
        '.list-item-additional-text_text[data-id="' + itemId + '"]'
      );
      textAdditionInput.setAttribute("contenteditable", "true");
      textAdditionInput.focus();
      const saveAdditionButtons = document.querySelector(
        '.list-item-additional-save[data-id="' + itemId + '"]'
      );
      saveAdditionButtons.style.display = "block";
      saveAdditionButtons.style.opacity = "1";
    };
  }

  const saveAdditionButtons = document.querySelectorAll(
    ".list-item-additional-save"
  );

  for (let i = 0; i < saveAdditionButtons.length; i++) {
    saveAdditionButtons[i].onclick = function () {
      const checkListItemId = this.getAttribute("data-id");
      const textAdditionInput = document.querySelector(
        '.list-item-additional-text_text[data-id="' + checkListItemId + '"]'
      );
      const request = new XMLHttpRequest();
      const url = "ajax/change_check_list_item_text.php";
      const params =
        "id=" + checkListItemId + "&text=" + textAdditionInput.innerText;
      request.open("POST", url, true);
      request.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
      );
      request.addEventListener("readystatechange", () => {
        if (request.readyState === 4 && request.status === 200) {
          console.log(request.responseText);
          this.style.display = "none";
          const textAdditionInput = document.querySelector(
            '.list-item-additional-text_text[data-id="' + itemId + '"]'
          );
          textAdditionInput.setAttribute("contenteditable", "false");

          const changeAdditionButton = document.querySelector(
            '.list-item-additional-change[data-id="' + itemId + '"]'
          );
          changeAdditionButton.style.display = "block";
        }
      });
      request.send(params);
    };
  }

  // описание изменения состояния чекбокса элемента чек листа

  const checkBoxes = document.querySelectorAll(
    ".main-block-check-list-item-name_checkbox"
  );

  for (let i = 0; i < checkBoxes.length; i++) {
    checkBoxes[i].onchange = function () {
      const checkListItemId = this.getAttribute("data-id");
      let checkListItemStatus;
      if (this.checked) {
        checkListItemStatus = 1;
      } else {
        checkListItemStatus = 0;
      }
      const request = new XMLHttpRequest();
      const url = "ajax/change_check_list_item_status.php";
      const params = "id=" + checkListItemId + "&status=" + checkListItemStatus;
      request.open("POST", url, true);
      request.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
      );
      request.addEventListener("readystatechange", () => {
        if (request.readyState === 4 && request.status === 200) {
          console.log(request.responseText);
        }
      });
      request.send(params);
    };
  }

  // описание изменения состояния подпунктов - чекбоксов элемента чек листа

  const itemCheckBoxes = document.querySelectorAll(
    ".list-item-additional-list_item"
  );

  for (let i = 0; i < itemCheckBoxes.length; i++) {
    itemCheckBoxes[i].onchange = changeCheckBox;
  }

  const openAddCheckBoxesBtn = document.querySelectorAll(
    ".list-item-additional-list-checkbox_add-btn"
  );

  let checkListItemId;

  for (let i = 0; i < openAddCheckBoxesBtn.length; i++) {
    openAddCheckBoxesBtn[i].onclick = function () {
      checkListItemId = this.getAttribute("data-id");
    };
  }

  const addCheckBoxesBtn = document.querySelectorAll(
    ".add-new-checklist-save_button"
  );

  for (let i = 0; i < addCheckBoxesBtn.length; i++) {
    addCheckBoxesBtn[i].onclick = function () {
      if (checkListItemId !== null || checkListItemId !== undefined) {
        const textAdditionInput = document.querySelectorAll(
          ".add-new-checklist-name_input"
        )[1];
        const text = textAdditionInput.value;
        const request = new XMLHttpRequest();
        const url = "ajax/add_new_check_item_item.php";
        const params = "id=" + checkListItemId + "&name=" + text;
        request.open("POST", url, true);
        request.setRequestHeader(
          "Content-type",
          "application/x-www-form-urlencoded"
        );
        let checkBoxId;
        request.addEventListener("readystatechange", () => {
          if (request.readyState === 4 && request.status === 200) {
            checkBoxId = Number(request.responseText);
            const checkBoxTag = '<input class="list-item-additional-list_item" type="checkbox" id="checkbox_' + checkBoxId + '" data-id=' + checkBoxId + ">";
            const checkBoxLabelTag = '<label for="checkbox_' + checkBoxId + '">' + text + "</label>";
            const checkBoxBlockTag = '<div class="list-item-additional-list-checkbox"></div>';
            const checkBoxesButtonn = document.querySelector( '.list-item-additional-list-checkbox_add-btn[data-id="' + checkListItemId + '"]' );
            const checkBoxElement = createElementFromHTML(checkBoxTag);
            const checkBoxLabelElement = createElementFromHTML(checkBoxLabelTag);
            const checkBoxBlockElement = createElementFromHTML(checkBoxBlockTag);
            checkBoxElement.onchange = changeCheckBox;
            checkBoxesButtonn.insertAdjacentElement(
              "beforeBegin",
              checkBoxBlockElement
            );
            checkBoxBlockElement.appendChild(checkBoxElement);
            checkBoxBlockElement.appendChild(checkBoxLabelElement);
          }
        });
        request.send(params);
      }
    };
  }
};

function createElementFromHTML(htmlString) {
  var div = document.createElement("div");
  div.innerHTML = htmlString.trim();

  return div.firstChild;
}

function changeCheckBox() {
  const checkListItemId = this.getAttribute("data-id");
  let checkListItemStatus;
  if (this.checked) {
    checkListItemStatus = 1;
  } else {
    checkListItemStatus = 0;
  }
  const request = new XMLHttpRequest();
  const url = "ajax/change_check_list_item_items_status.php";
  const params = "id=" + checkListItemId + "&status=" + checkListItemStatus;
  request.open("POST", url, true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.addEventListener("readystatechange", () => {
    if (request.readyState === 4 && request.status === 200) {
      console.log(request.responseText);
    }
  });
  request.send(params);
}
