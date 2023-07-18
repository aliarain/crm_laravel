
var page   = 1;
var is_render = true;
const pageNumbers = (total, max, current) => {
    const half = Math.floor(max / 2);
    let to = max;
    
    if(current + half >= total) {
      to = total;
    } else if(current > half) {
      to = current + half ;
    }
    
    let from = Math.max(to - max, 0);
  
    return Array.from({length: Math.min(total, max)}, (_, i) => (i + 1) + from);
  }
  
  function PaginationButton(totalPages, className , maxPagesVisible = 10, currentPage = 1) {

    let pages = pageNumbers(totalPages, maxPagesVisible, currentPage);
    let currentPageBtn = null;
    const buttons = new Map();
    const disabled = {
      start: () => pages[0] === 1,
      prev: () => currentPage === 1 || currentPage > totalPages,
      end: () => pages.slice(-1)[0] === totalPages,
      next: () => currentPage >= totalPages
    }
    // js to jquery

    const elements = `<div class="pagination-wrapper">
                       <nav aria-label="Page navigation example">
                          <ul class="pagination">
                          </ul>
                        </nav>
                      </div>`;


    
    const frag = document.createDocumentFragment();
    const paginationButtonContainer = document.createElement('div');
    paginationButtonContainer.className = 'ot-pagination d-flex justify-content-end align-content-center mt-5';

    // add a nav under paginationButtonContainer
    const nav = document.createElement('nav');
    nav.className = 'ot-pagination-nav';
    // add a ul under nav
    const ul = document.createElement('ul');
    ul.className = 'pagination';
    nav.appendChild(ul);
    paginationButtonContainer.appendChild(nav);

    console.log(paginationButtonContainer);
    



    
    const createAndSetupButton = (label = '', cls = '', disabled = false, handleClick, icon = null) => {
      //add li element under nav
      const buttonElement = document.createElement('a');      
      buttonElement.className = `page-link ${cls}`;
      buttonElement.disabled = disabled;
      // add icon if exists
      if (icon) {
        const iconElement = document.createElement('i');
        iconElement.className = `${icon}`;
        buttonElement.appendChild(iconElement);
      }else{
         buttonElement.textContent = label;
      }
      buttonElement.addEventListener('click', e => {
        handleClick(e);
        this.update();
        
        paginationButtonContainer.value = currentPage;
        
        paginationButtonContainer.dispatchEvent(new CustomEvent('change', {detail: {currentPageBtn}}));
      });
      
      return buttonElement;
    }
    
    const onPageButtonClick = e => currentPage = Number(e.currentTarget.textContent);
    
    const onPageButtonUpdate = index => (btn) => {
      btn.textContent = pages[index];
      
      if(pages[index] === currentPage) {
        currentPageBtn.classList.remove('active');
        btn.classList.add('active');
        currentPageBtn = btn;
        currentPageBtn.focus();
      }
    };

    

    
    buttons.set(
      createAndSetupButton('prev', 'prev-page', disabled.prev(), () => currentPage -= 1, 'fa fa-angle-left'),
      (btn) => btn.disabled = disabled.prev()
    )
    
    pages.map((pageNumber, index) => {
      const isCurrentPage = currentPage === pageNumber;
      if(index > 6){
        return;
      }
      if (index === 5) {
        var button = createAndSetupButton(
          '...', isCurrentPage ? 'active' : '', false, onPageButtonClick
        );
      }else {
        var button = createAndSetupButton(
          pageNumber, isCurrentPage ? 'active' : '', false, onPageButtonClick
        );
        
      } 
      
      if(isCurrentPage) {
        currentPageBtn = button;
      }
      
      buttons.set(button, onPageButtonUpdate(index));
    });
    
    buttons.set(
      createAndSetupButton('next', 'next-page', disabled.next(), () => currentPage += 1),
      (btn) => btn.disabled = disabled.next()
    )

    buttons.forEach(button => {
      console.log(button);
      
    });
    
    
    buttons.forEach((_, btn) => frag.appendChild(btn));
    paginationButtonContainer.appendChild(frag);
    
    this.render = (container = document.body) => {
        $('.'+className).after(paginationButtonContainer);
    }
    
    this.update = (newPageNumber = currentPage) => {
      currentPage = newPageNumber;
      pages = pageNumbers(totalPages, maxPagesVisible, currentPage);
      buttons.forEach((updateButton, btn) => updateButton(btn));
    }
    
    this.onChange = (handler) => {
      paginationButtonContainer.addEventListener('change', handler);
    }
  }


  function table(table_info, page = 1) {
      $.ajax({
          url: table_info['url'] + '?page=' + page ?? 1,
          type: "GET",
          data: table_info['value'],
          success: function (response) {
              if(response?.data?.length > 0){
                  let html = '';
                  html += '<tbody>';
                  for (let i = 0; i < response.data.length; i++) {
                      html += '<tr>';
                      for (let j = 0; j < table_info['column'].length; j++) {
                          html += '<td>' + response.data[i][table_info['column'][j]] + '</td>';
                      }
                      html += '</tr>';
                  }
                  html += '</tbody>';
                  $("." + table_info['table_id']).find('thead').after('');
                  $("." + table_info['table_id']).find('thead').after(html);
                  const paginationButtons = new PaginationButton(response?.pagination?.total_pages ?? 1 ,table_info['table_id'],10);  
                  is_render ? paginationButtons.render() : '' 
                  paginationButtons.onChange(e => {
                    is_render = false;
                    $('.'+table_info['table_id']).find('tbody').html('');
                    table(table_info, e.target.value);
                  });
              }else{
                  $("." + table_info['table_id']).children().after(`<tbody>
                                                                        <tr class="odd">
                                                                          <td valign="top" colspan="${table_info['column'].length}" class="dataTables_empty">
                                                                              <div class="no-data-found-wrapper text-center ">
                                                                                <img src="${baseUrl}/public/images/no_data.svg" alt="" class="mb-primary">
                                                                                <p class="mb-0 text-center">No data found !</p> 
                                                                              </div>
                                                                          </td>
                                                                        </tr>
                                                                    </tbody>`);
              }
          },
          error: function (error) {
              if (error.responseJSON.message) {                    
                  Swal.fire({
                      title: error.responseJSON.message,
                      type: 'error',
                      icon: 'error',
                      timer: 3000
                  });
              };
          },
      });
  }
