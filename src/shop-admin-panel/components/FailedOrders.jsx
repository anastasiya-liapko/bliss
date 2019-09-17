import React, { Component } from 'react';
import FailedOrdersTable from './FailedOrdersTable.jsx';
import FailedOrder from './FailedOrder.jsx';
import Helper from './Helper.jsx';

class FailedOrders extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      item: [],
      showItem: false,
      items: [],
      orderBy: '',
      filterBy: '',
      filterStart: '',
      filterEnd: '',
      isDescending: true,
      total: 0,
      totalSum: 0,
      pageCount: 0,
      offset: 0,
    };

    this.fetchItems = this.fetchItems.bind(this);
    this.fetchItem = this.fetchItem.bind(this);
    this.handlePageClick = this.handlePageClick.bind(this);
    this.handleSort = this.handleSort.bind(this);
    this.handleFilter = this.handleFilter.bind(this);
    this.handleFilterClose = this.handleFilterClose.bind(this);
    this.handleShowItem = this.handleShowItem.bind(this);
    this.handleCloseItem = this.handleCloseItem.bind(this);
  }

  componentDidMount() {
    this.setState({
      isLoading: true,
    }, () => {
      this.fetchItems();
    });
  }

  fetchItems() {
    const {
      offset,
      orderBy,
      filterBy,
      filterStart,
      filterEnd,
      isDescending,
    } = this.state;

    const url = `${Helper.getLocation()}/shop-admin-panel/failed-orders/get-failed-orders`
      + `?per_page=${this.props.perPage}`
      + `&offset=${offset}`
      + `&sort=${(isDescending ? 'desc' : 'asc')}`
      + `&sort_by=${orderBy}`
      + `&filter_by=${filterBy}`
      + `&filter_start=${filterStart}`
      + `&filter_end=${filterEnd}`;

    fetch(url, {
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
    })
      .then(response => response.json())
      .then(
        /**
         * @param result
         * @param result.data
         * @param result.data.items
         * @param result.data.statistic
         * @param result.data.statistics.total
         * @param result.data.statistics.total_cost
         */
        (result) => {
          this.setState({
            isLoading: false,
            items: result.data.items,
            total: result.data.statistics.total,
            totalSum: result.data.statistics.total_cost,
            pageCount: Math.ceil(result.data.statistics.total / this.props.perPage),
          });
        },
        (error) => {
          this.setState({ error, isLoading: false });
        },
      );
  }

  fetchItem(orderId) {
    const url = `${Helper.getLocation()}/shop-admin-panel/failed-orders/get-order?id=${orderId}`;

    fetch(url, {
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
    })
      .then(response => response.json())
      .then(
        (result) => {
          this.setState({
            isLoading: false,
            item: result.data.item,
          });
        },
        (error) => {
          this.setState({ error, isLoading: false });
        },
      );
  }

  handlePageClick(data) {
    this.setState({
      error: null,
      offset: Math.ceil(data.selected * this.props.perPage),
    }, () => {
      this.fetchItems();
    });
  }

  handleSort(orderBy) {
    this.setState({
      error: null,
      orderBy,
      offset: 0,
      isDescending: !this.state.isDescending,
    }, () => {
      this.fetchItems();
    });
  }

  handleFilter(filterBy, start, end) {
    this.setState({
      error: null,
      filterBy,
      filterStart: start,
      filterEnd: end,
      offset: 0,
    }, () => {
      this.fetchItems();
    });
  }

  handleFilterClose() {
    this.setState({
      error: null,
      filterBy: '',
      filterStart: '',
      filterEnd: '',
      offset: 0,
    }, () => {
      this.fetchItems();
    });
  }

  handleShowItem(orderId) {
    this.setState({
      error: null,
      isLoading: true,
      showItem: true,
    }, () => {
      this.fetchItem(orderId);
    });
  }

  handleCloseItem() {
    this.setState({ showItem: false });
  }

  render() {
    const {
      error,
      isLoading,
      showItem,
      item,
      items,
      orderBy,
      isDescending,
      filterBy,
      filterStart,
      filterEnd,
      total,
      totalSum,
      pageCount,
      offset,
    } = this.state;

    if (showItem) {
      return (
        <FailedOrder
          error={error}
          isLoading={isLoading}
          item={item}
          onClose={this.handleCloseItem}/>
      );
    }

    return (
      <FailedOrdersTable
        error={error}
        isLoading={isLoading}
        items={items}
        orderBy={orderBy}
        isDescending={isDescending}
        filterBy={filterBy}
        filterName={Helper.getFilterName(filterBy)}
        filterStart={filterStart}
        filterEnd={filterEnd}
        totalItems={total}
        totalSum={totalSum}
        pageCount={pageCount}
        offset={offset}
        onFilterCloseClick={this.handleFilterClose}
        onTableSortClick={this.handleSort}
        onTableFilterClick={this.handleFilter}
        onTableRowClick={this.handleShowItem}
        onPageClick={this.handlePageClick}/>
    );
  }
}

export default FailedOrders;
