import React, { Component } from 'react';
import Overlay from 'react-bootstrap/Overlay';
import Popover from 'react-bootstrap/Popover';
import TableFilterForm from './TableFilterForm.jsx';

class TableHeader extends Component {
  constructor(props) {
    super(props);

    this.state = {
      target: null,
      showOverlay: false,
    };

    this.attachRef = this.attachRef.bind(this);
    this.handleSortClick = this.handleSortClick.bind(this);
    this.handleFilterClick = this.handleFilterClick.bind(this);
    this.handleFilterClose = this.handleFilterClose.bind(this);
  }

  attachRef(target) {
    this.setState({ target });
  }

  handleSortClick() {
    const { slug } = this.props;

    this.props.onTableSortClick(slug);
  }

  handleFilterClick(start, end) {
    const { slug } = this.props;

    this.setState({ showOverlay: false });
    this.props.onTableFilterClick(slug, start, end);
  }

  handleFilterClose() {
    this.setState({ showOverlay: !this.state.showOverlay });
  }

  render() {
    let className = 'table__th text-nowrap';
    const {
      active,
      isDescending,
      name,
      filterInputType,
      slug,
      values,
      showSort,
      showFilter,
    } = this.props;
    const { target, showOverlay } = this.state;

    if (active && isDescending) {
      className += ' table__th_sort-down';
    } else if (active && !isDescending) {
      className += ' table__th_sort-up';
    }

    return (
      <th className={className}>
        {showSort && <button
          type="button"
          className="btn btn-link table__sort-button"
          title="Сортировка"
          onClick={this.handleSortClick}>
          <i className="fas fa-sort-up" aria-hidden={true}/>
          <i className="fas fa-sort-down" aria-hidden={true}/>
        </button>}
        {name}
        {showFilter && <Overlay
          target={target}
          show={showOverlay}
          rootClose={true}
          placement="bottom"
          onHide={this.handleFilterClose}>
          <Popover>
            <TableFilterForm
              type={filterInputType}
              inputName={slug}
              values={values || []}
              onTableFilterClick={this.handleFilterClick}/>
          </Popover>
        </Overlay>}
        {showFilter && <button
          type="button"
          ref={this.attachRef}
          className="btn btn-link table__filter-button"
          title="Фильтрация"
          onClick={this.handleFilterClose}>
          <i className='fas fa-filter'/>
        </button>}
      </th>
    );
  }
}

export default TableHeader;
