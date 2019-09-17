import React, { Component } from 'react';
import ReactPaginate from 'react-paginate';

class TablePagination extends Component {
  constructor(props) {
    super(props);

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(date) {
    this.props.onPageClick(date);
  }

  render() {
    const { pageCount, offset, perPage } = this.props;

    if (pageCount < 2) {
      return null;
    }

    return (
      <ReactPaginate
        forcePage={offset / perPage}
        pageCount={pageCount}
        pageRangeDisplayed={5}
        marginPagesDisplayed={2}
        onPageChange={this.handleClick}
        previousLabel="назад"
        nextLabel="вперёд"
        breakLabel="..."
        containerClassName="pagination"
        pageClassName="page-item"
        previousClassName="page-item"
        nextClassName="page-item"
        breakClassName="page-item"
        pageLinkClassName="page-link"
        previousLinkClassName="page-link"
        nextLinkClassName="page-link"
        breakLinkClassName="page-link"
        activeClassName="active"/>
    );
  }
}

export default TablePagination;
