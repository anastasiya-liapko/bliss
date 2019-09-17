import React, { Component } from 'react';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Popover from 'react-bootstrap/Popover';
import DeliveringOrder from './DeliveringOrder.jsx';
import DeliveringOrdersTable from './DeliveringOrdersTable.jsx';
import Helper from './Helper.jsx';

class DeliveringOrders extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      currentTabName: 'firstTab',
      firstTabItem: [],
      firstTabShowItem: false,
      firstTabItems: [],
      firstTabOrderBy: '',
      firstTabFilterBy: '',
      firstTabFilterStart: '',
      firstTabFilterEnd: '',
      firstTabIsDescending: true,
      firstTabTotal: 0,
      firstTabTotalSum: 0,
      firstTabPageCount: 0,
      firstTabOffset: 0,
      secondTabItem: [],
      secondTabShowItem: false,
      secondTabItems: [],
      secondTabOrderBy: '',
      secondTabFilterBy: '',
      secondTabFilterStart: '',
      secondTabFilterEnd: '',
      secondTabIsDescending: true,
      secondTabTotal: 0,
      secondTabTotalSum: 0,
      secondTabPageCount: 0,
      secondTabOffset: 0,
    };

    this.handleChangeTab = this.handleChangeTab.bind(this);
    this.getFetchItemsUrl = this.getFetchItemsUrl.bind(this);
    this.fetchItems = this.fetchItems.bind(this);
    this.fetchItem = this.fetchItem.bind(this);
    this.handlePageClick = this.handlePageClick.bind(this);
    this.handleSort = this.handleSort.bind(this);
    this.handleFilter = this.handleFilter.bind(this);
    this.handleFilterClose = this.handleFilterClose.bind(this);
    this.handleShowItem = this.handleShowItem.bind(this);
    this.handleCloseItem = this.handleCloseItem.bind(this);
    this.handleReloadItems = this.handleReloadItems.bind(this);
  }

  componentDidMount() {
    this.setState({
      isLoading: true,
    }, () => {
      this.fetchItems();
    });
  }

  handleChangeTab(event) {
    const { currentTabName } = this.state;
    const currentTargetId = event.currentTarget.id;

    if (currentTabName === currentTargetId && !this.state[`${currentTargetId}ShowItem`]) {
      return;
    }

    this.setState({
      currentTabName: currentTargetId,
      [`${currentTargetId}ShowItem`]: false,
    }, () => {
      this.fetchItems();
    });
  }

  getFetchItemsUrl() {
    let method;

    const { currentTabName } = this.state;
    const offset = this.state[`${currentTabName}Offset`];
    const orderBy = this.state[`${currentTabName}OrderBy`];
    const filterBy = this.state[`${currentTabName}FilterBy`];
    const filterStart = this.state[`${currentTabName}FilterStart`];
    const filterEnd = this.state[`${currentTabName}FilterEnd`];
    const isDescending = this.state[`${currentTabName}IsDescending`];

    if (currentTabName === 'firstTab') {
      method = 'get-manual-delivering-orders';
    } else if (currentTabName === 'secondTab') {
      method = 'get-auto-delivering-orders';
    }

    return `${Helper.getLocation()}/shop-admin-panel/delivering-orders/${method}`
      + `?per_page=${this.props.perPage}`
      + `&offset=${offset}`
      + `&sort=${(isDescending ? 'desc' : 'asc')}`
      + `&sort_by=${orderBy}`
      + `&filter_by=${filterBy}`
      + `&filter_start=${filterStart}`
      + `&filter_end=${filterEnd}`;
  }

  fetchItems() {
    const { currentTabName } = this.state;

    fetch(this.getFetchItemsUrl(), {
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
            [`${currentTabName}Items`]: result.data.items,
            [`${currentTabName}Total`]: result.data.statistics.total,
            [`${currentTabName}TotalSum`]: result.data.statistics.total_cost,
            [`${currentTabName}PageCount`]: Math.ceil(result.data.statistics.total / this.props.perPage),
          });
        },
        (error) => {
          this.setState({ error, isLoading: false });
        },
      );
  }

  fetchItem(orderId) {
    const { currentTabName } = this.state;
    const url = `${Helper.getLocation()}/shop-admin-panel/delivering-orders/get-order?id=${orderId}`;

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
            [`${currentTabName}Item`]: result.data.item,
          });
        },
        (error) => {
          this.setState({ error, isLoading: false });
        },
      );
  }

  handlePageClick(data) {
    const { currentTabName } = this.state;

    this.setState({
      error: null,
      [`${currentTabName}Offset`]: Math.ceil(data.selected * this.props.perPage),
    }, () => {
      this.fetchItems();
    });
  }

  handleSort(orderBy) {
    const { currentTabName } = this.state;

    this.setState({
      error: null,
      [`${currentTabName}OrderBy`]: orderBy,
      [`${currentTabName}Offset`]: 0,
      [`${currentTabName}IsDescending`]: !this.state[`${currentTabName}IsDescending`],
    }, () => {
      this.fetchItems();
    });
  }

  handleFilter(filterBy, start, end) {
    const { currentTabName } = this.state;

    this.setState({
      error: null,
      [`${currentTabName}FilterBy`]: filterBy,
      [`${currentTabName}FilterStart`]: start,
      [`${currentTabName}FilterEnd`]: end,
      [`${currentTabName}Offset`]: 0,
    }, () => {
      this.fetchItems();
    });
  }

  handleFilterClose() {
    const { currentTabName } = this.state;

    this.setState({
      error: null,
      [`${currentTabName}FilterBy`]: '',
      [`${currentTabName}FilterStart`]: '',
      [`${currentTabName}FilterEnd`]: '',
      [`${currentTabName}Offset`]: 0,
    }, () => {
      this.fetchItems();
    });
  }

  handleShowItem(orderId) {
    const { currentTabName } = this.state;

    this.setState({
      error: null,
      isLoading: true,
      [`${currentTabName}ShowItem`]: true,
    }, () => {
      this.fetchItem(orderId);
    });
  }

  handleCloseItem(isReload) {
    const { currentTabName } = this.state;

    this.setState({
      [`${currentTabName}ShowItem`]: false,
    });

    if (isReload) {
      this.handleReloadItems();
    }
  }

  handleReloadItems() {
    const { currentTabName } = this.state;

    this.setState({
      isLoading: true,
      [`${currentTabName}Offset`]: 0,
    }, () => {
      this.fetchItems();
    });
  }

  render() {
    const {
      error,
      isLoading,
      firstTabShowItem,
      firstTabItem,
      firstTabItems,
      firstTabOrderBy,
      firstTabIsDescending,
      firstTabFilterBy,
      firstTabFilterStart,
      firstTabFilterEnd,
      firstTabTotal,
      firstTabTotalSum,
      firstTabPageCount,
      firstTabOffset,
      secondTabShowItem,
      secondTabItem,
      secondTabItems,
      secondTabOrderBy,
      secondTabIsDescending,
      secondTabFilterBy,
      secondTabFilterStart,
      secondTabFilterEnd,
      secondTabTotal,
      secondTabTotalSum,
      secondTabPageCount,
      secondTabOffset,
    } = this.state;

    let firstTabInner;
    let secondTabInner;

    if (firstTabShowItem) {
      firstTabInner = <DeliveringOrder
        error={error}
        isLoading={isLoading}
        item={firstTabItem}
        showButtons={true}
        onClose={this.handleCloseItem}/>;
    } else {
      firstTabInner = <DeliveringOrdersTable
        hasEditButton={true}
        error={error}
        isLoading={isLoading}
        items={firstTabItems}
        orderBy={firstTabOrderBy}
        isDescending={firstTabIsDescending}
        filterBy={firstTabFilterBy}
        filterName={Helper.getFilterName(firstTabFilterBy)}
        filterStart={firstTabFilterStart}
        filterEnd={firstTabFilterEnd}
        totalItems={firstTabTotal}
        totalSum={firstTabTotalSum}
        pageCount={firstTabPageCount}
        offset={firstTabOffset}
        onFilterCloseClick={this.handleFilterClose}
        onTableSortClick={this.handleSort}
        onTableFilterClick={this.handleFilter}
        onTableRowClick={this.handleShowItem}
        onPageClick={this.handlePageClick}
        onReloadItems={this.handleReloadItems}/>;
    }

    if (secondTabShowItem) {
      secondTabInner = <DeliveringOrder
        error={error}
        isLoading={isLoading}
        item={secondTabItem}
        showButtons={false}
        onClose={this.handleCloseItem}/>;
    } else {
      secondTabInner = <DeliveringOrdersTable
        error={error}
        isLoading={isLoading}
        items={secondTabItems}
        orderBy={secondTabOrderBy}
        isDescending={secondTabIsDescending}
        filterBy={secondTabFilterBy}
        filterName={Helper.getFilterName(secondTabFilterBy)}
        filterStart={secondTabFilterStart}
        filterEnd={secondTabFilterEnd}
        totalItems={secondTabTotal}
        totalSum={secondTabTotalSum}
        pageCount={secondTabPageCount}
        offset={secondTabOffset}
        onFilterCloseClick={this.handleFilterClose}
        onTableSortClick={this.handleSort}
        onTableFilterClick={this.handleFilter}
        onTableRowClick={this.handleShowItem}
        onPageClick={this.handlePageClick}
        onReloadItems={this.handleReloadItems}/>;
    }

    return (
      <div>
        <nav className="nav nav-tabs" role="tablist">
          <a onClick={this.handleChangeTab} className="nav-item nav-link active" id="firstTab" data-toggle="tab"
             href="#firstTabPane" role="tab" aria-controls="firstTabPane" aria-selected="true">
            Ручной контроль
            <OverlayTrigger
              trigger="hover"
              placement="bottom"
              delay={{ show: 250, hide: 400 }}
              overlay={
                <Popover>
                  Отображаются заказы, переданные на доставку. Путь к покупателю по этим заказам мы
                  пока не умеем отслеживать, но работаем над этим :) Как только вы узнаете,
                  что заказ доставлен покупателю, подтвердите это, и вам будет перечислена оплата
                  от финансовой организации, а заказ перемещен в раздел оплаченных заказов.
                </Popover>
              }>
              <i className="far fa-question-circle pl-2 text-muted" aria-hidden="true"/>
            </OverlayTrigger>
          </a>
          <a onClick={this.handleChangeTab} className="nav-item nav-link" id="secondTab" data-toggle="tab"
             href="#secondTabPane" role="tab" aria-controls="secondTabPane" aria-selected="false">
            Автоматический контроль
            <OverlayTrigger
              trigger="hover"
              placement="bottom"
              delay={{ show: 250, hide: 400 }}
              overlay={
                <Popover>
                  Отображаются заказы, переданные на доставку. Мы умеем отслеживать их путь к
                  покупателю. Как только заказ будет доставлен, мы сообщим об этом финансовой
                  организации, и она перечислит вам оплату, а заказ будет перемещен в раздел
                  оплаченных заказов.
                </Popover>
              }>
              <i className="far fa-question-circle pl-2 text-muted" aria-hidden="true"/>
            </OverlayTrigger>
          </a>
        </nav>
        <div className="tab-content">
          <div className="tab-pane fade show active" id="firstTabPane" role="tabpanel" aria-labelledby="firstTab">
            {firstTabInner}
          </div>
          <div className="tab-pane fade" id="secondTabPane" role="tabpanel" aria-labelledby="secondTab">
            {secondTabInner}
          </div>
        </div>

      </div>
    );
  }
}

export default DeliveringOrders;
