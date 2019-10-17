      var listElement
      var selectElement = document.querySelector('#participantSelect')
      var buttonElement = document.querySelector('#sendToList');

      var listSelected = JSON.parse(localStorage.getItem('listStored'))

      function addToList() {
            var list = selectElement.value;
            listSelected.push(list);
            selectElement.value = '';
            renderList();
            saveToStorage();
      }
      buttonElement.onclick = addToList;

      function deleteFormList(pos) {
            listSelected.splice(pos, 1);
            renderList();
            saveToStorage();
      }

      function saveToStorage() {
            localStorage.setItem('listStored', JSON.stringify(listSelected));
      }