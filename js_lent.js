const paginationNumbers = document.getElementById("pagination-numbers");
const paginatedList = document.getElementById("paginated-list");
const listItems = paginatedList.querySelectorAll("li");

const paginationLimit = 10;
const pageCount = Math.ceil(listItems.length / paginationLimit);
let currentPage = 1;

const nextButton = document.getElementById("next-button");
const prevButton = document.getElementById("prev-button");

const disableButton = (button) => {
  button.classList.add("disabled");
  button.setAttribute("disabled", true);
};

const enableButton = (button) => {
  button.classList.remove("disabled");
  button.removeAttribute("disabled");
};

const handlePageButtonsStatus = () => {
  if (currentPage === 1) {
    disableButton(prevButton);
  } else {
    enableButton(prevButton);
  }

  if (pageCount === currentPage) {
    disableButton(nextButton);
  } else {
    enableButton(nextButton);
  }
};

const appendPageNumber = (index) => {
  const pageNumber = document.createElement("button");
  pageNumber.className = "pagination-number";
  pageNumber.innerHTML = index;
  pageNumber.setAttribute("page-index", index);
  pageNumber.setAttribute("aria-label", "Page " + index);

  paginationNumbers.appendChild(pageNumber);
};

const getPaginationNumbers = () => {
  for (let i = 1; i <= pageCount; i++) {
    appendPageNumber(i);
  }
};

const handleActivePageNumber = () => {
  document.querySelectorAll(".pagination-number").forEach((button) => {
    button.classList.remove("active");
    const pageIndex = Number(button.getAttribute("page-index"));
    if (pageIndex == currentPage) {
      button.classList.add("active");
    }
  });
};

const setCurrentPage = (pageNum) => {
  currentPage = pageNum;
  handleActivePageNumber();
  handlePageButtonsStatus();

  const prevRange = (pageNum - 1) * paginationLimit;
  const currRange = pageNum * paginationLimit;

  listItems.forEach((item, index) => {
    item.classList.add("hide");
    if (index >= prevRange && index < currRange) {
      item.classList.remove("hide");
    }
  });
};

window.addEventListener("load", () => {
  getPaginationNumbers();
  setCurrentPage(1);

  prevButton.addEventListener("click", () => {

    setCurrentPage(currentPage - 1);

  });

  nextButton.addEventListener("click", () => {

    setCurrentPage(currentPage + 1);

  });


  document.querySelectorAll(".pagination-number").forEach((button) => {
    const pageIndex = Number(button.getAttribute("page-index"));

    if (pageIndex) {
      button.addEventListener("click", () => {
        setCurrentPage(pageIndex);
      });
    }
  });
});


const paginatedLisst = document.getElementById("paginated-list");   
const listText = paginatedLisst.querySelectorAll("#aff");

listText.forEach((item, char) => {


if (item.innerText.length > 12) {
  item.classList.add("resp-font");
}
if(item.innerText.length>21){
    item.classList.remove("resp-font");
    item.classList.add("resp-mega-font");
}
if(item.innerText.length>25){
    item.classList.remove("resp-mega-font");
    item.classList.add("resp-mega-mega-font");
}
if(item.innerText.length>29){
    item.classList.remove("resp-mega-mega-font");
    item.classList.add("resp-mega-mega-mega-font");
}
if(item.innerText.length>34){
    item.classList.remove("resp-mega-mega-mega-font");
    item.classList.add("resp-mega-mega-mega-mega-font");
}

});
