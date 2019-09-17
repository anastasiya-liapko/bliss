import React, { Component } from 'react';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Popover from 'react-bootstrap/Popover';
import IncomingOrdersTable from './IncomingOrdersTable.jsx';
import IncomingOrder from './IncomingOrder.jsx';
import Helper from './Helper.jsx';
import ModalCreate from './ModalCreate.jsx';

class IncomingOrders extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      currentTabName: 'firstTab',
      showCreateModal: false,
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
      thirdTabItem: [],
      thirdTabShowItem: false,
      thirdTabItems: [],
      thirdTabOrderBy: '',
      thirdTabFilterBy: '',
      thirdTabFilterStart: '',
      thirdTabFilterEnd: '',
      thirdTabIsDescending: true,
      thirdTabTotal: 0,
      thirdTabTotalSum: 0,
      thirdTabPageCount: 0,
      thirdTabOffset: 0,
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
    this.handleShowCreateModal = this.handleShowCreateModal.bind(this);
    this.handleHideCreateModal = this.handleHideCreateModal.bind(this);
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
      method = 'get-pending-orders';
    } else if (currentTabName === 'secondTab') {
      method = 'get-potential-orders';
    } else if (currentTabName === 'thirdTab') {
      method = 'get-created-orders';
    }

    return `${Helper.getLocation()}/shop-admin-panel/incoming-orders/${method}`
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
    const url = `${Helper.getLocation()}/shop-admin-panel/incoming-orders/get-order?id=${orderId}`;

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

  handleShowCreateModal(event) {
    event.target.blur();

    this.setState({ showCreateModal: true });
  }

  handleHideCreateModal() {
    this.setState({ showCreateModal: false });
  }

  render() {
    const {
      error,
      isLoading,
      showCreateModal,
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
      thirdTabShowItem,
      thirdTabItem,
      thirdTabItems,
      thirdTabOrderBy,
      thirdTabIsDescending,
      thirdTabFilterBy,
      thirdTabFilterStart,
      thirdTabFilterEnd,
      thirdTabTotal,
      thirdTabTotalSum,
      thirdTabPageCount,
      thirdTabOffset,
    } = this.state;

    let firstTabInner;
    let secondTabInner;
    let thirdTabInner;

    if (firstTabShowItem) {
      firstTabInner = <IncomingOrder
        error={error}
        isLoading={isLoading}
        item={firstTabItem}
        showButtons={true}
        onClose={this.handleCloseItem}/>;
    } else {
      firstTabInner = <IncomingOrdersTable
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
        filterByStatus={false}
        onFilterCloseClick={this.handleFilterClose}
        onTableSortClick={this.handleSort}
        onTableFilterClick={this.handleFilter}
        onTableRowClick={this.handleShowItem}
        onPageClick={this.handlePageClick}
        onReloadItems={this.handleReloadItems}/>;
    }

    if (secondTabShowItem) {
      secondTabInner = <IncomingOrder
        error={error}
        isLoading={isLoading}
        item={secondTabItem}
        showButtons={false}
        onClose={this.handleCloseItem}/>;
    } else {
      secondTabInner = <IncomingOrdersTable
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
        filterByStatus={true}
        onFilterCloseClick={this.handleFilterClose}
        onTableSortClick={this.handleSort}
        onTableFilterClick={this.handleFilter}
        onTableRowClick={this.handleShowItem}
        onPageClick={this.handlePageClick}
        onReloadItems={this.handleReloadItems}/>;
    }

    if (thirdTabShowItem) {
      thirdTabInner = <IncomingOrder
        error={error}
        isLoading={isLoading}
        item={thirdTabItem}
        showButtons={false}
        onClose={this.handleCloseItem}/>;
    } else {
      thirdTabInner = <IncomingOrdersTable
        error={error}
        isLoading={isLoading}
        items={thirdTabItems}
        orderBy={thirdTabOrderBy}
        isDescending={thirdTabIsDescending}
        filterBy={thirdTabFilterBy}
        filterName={Helper.getFilterName(thirdTabFilterBy)}
        filterStart={thirdTabFilterStart}
        filterEnd={thirdTabFilterEnd}
        totalItems={thirdTabTotal}
        totalSum={thirdTabTotalSum}
        pageCount={thirdTabPageCount}
        offset={thirdTabOffset}
        filterByStatus={false}
        onFilterCloseClick={this.handleFilterClose}
        onTableSortClick={this.handleSort}
        onTableFilterClick={this.handleFilter}
        onTableRowClick={this.handleShowItem}
        onPageClick={this.handlePageClick}
        onReloadItems={this.handleReloadItems}/>;
    }

    return (
      <div>

        <div className="text-right mb-3">
          <button
            type="button"
            className="btn btn-primary"
            onClick={this.handleShowCreateModal}>
            Создать заказ
          </button>
        </div>

        <nav className="nav nav-tabs" role="tablist">
          <a onClick={this.handleChangeTab} className="nav-item nav-link active" id="firstTab" data-toggle="tab"
             href="#firstTabPane" role="tab" aria-controls="firstTabPane" aria-selected="true">
            Ожидают подтверждения
            <OverlayTrigger
              trigger="hover"
              placement="bottom"
              delay={{ show: 250, hide: 400 }}
              overlay={
                <Popover>
                  Отображаются заказы, требующие подтверждения возможности доставки. Убедитесь,
                  что товары, входящие в состав заказа есть в наличии, и покупатель сможет их
                  получить. После этого передайте заказ на доставку. Если по каким-то причинам
                  вы не сможете выдать заказ покупателю — отклоните его.
                </Popover>
              }>
              <i className="far fa-question-circle pl-2 text-muted" aria-hidden="true"/>
            </OverlayTrigger>
          </a>
          <a onClick={this.handleChangeTab} className="nav-item nav-link" id="secondTab" data-toggle="tab"
             href="#secondTabPane" role="tab" aria-controls="secondTabPane" aria-selected="false">
            Потенциальные заказы
            <OverlayTrigger
              trigger="hover"
              placement="bottom"
              delay={{ show: 250, hide: 400 }}
              overlay={
                <Popover>
                  Отображаются заказы, по кредитованию которых ожидается решение от финансовых
                  организаций и заказы, кредитование которых уже одобрено, но не подтверждено
                  покупателем. Если заказ висит в неподтвержденных покупателем продолжительное
                  время, вы можете с ним связаться и постараться ускорить получение подтверждения.
                </Popover>
              }>
              <i className="far fa-question-circle pl-2 text-muted" aria-hidden="true"/>
            </OverlayTrigger>
          </a>
          <a onClick={this.handleChangeTab} className="nav-item nav-link" id="thirdTab" data-toggle="tab"
             href="#thirdTabPane" role="tab" aria-controls="thirdTabPane" aria-selected="false">
            Созданные заказы
            <OverlayTrigger
              trigger="hover"
              placement="bottom"
              delay={{ show: 250, hide: 400 }}
              overlay={
                <Popover>
                  В этом разделе вы можете создать заказ для оффлайн-магазина.
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
          <div className="tab-pane fade" id="thirdTabPane" role="tabpanel" aria-labelledby="thirdTab">
            {thirdTabInner}
          </div>
        </div>

        <ModalCreate
          show={showCreateModal}
          url={`${Helper.getLocation()}/shop-admin-panel/incoming-orders/create`}
          onHide={this.handleHideCreateModal}
          onFinish={this.handleReloadItems}/>

      </div>
    );
  }
}

export default IncomingOrders;
