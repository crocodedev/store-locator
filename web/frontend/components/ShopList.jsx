import {useEffect, useState, useCallback} from 'react'
import {useNavigate} from 'react-router-dom'
import {
    SortMinor,
    SearchMajor,
    MobilePlusMajor,
    FilterMajor
} from '@shopify/polaris-icons'
import {
    IndexTable,
    useIndexResourceState,
    Page,
    TextField,
    Icon,
    Button,
    Card,
    Popover,
    ChoiceList,
    Listbox
} from '@shopify/polaris'
import ShopItem from './ShopItem'
import ModalDropZone from './ModalDropZone'
import SkeletonShopList from './SkeletonShopList'
import {useAppQuery, useAuthenticatedFetch} from "../hooks";
import ModalEdit from "./ModalEdit";
import CustomButton from "./CustomButton";

export const ShopList = ({shops, refetchStories, setValue, loading, textFieldValue, handleTextFieldChange, handleClearButtonClick }) => {
    const [active, setActive] = useState('All')
    const [activeSearch, setActiveSearch] = useState(false)
    const [activeModal, setActiveModal] = useState(false)
    const [files, setFiles] = useState([])
    const [popoverActive, setPopoverActive] = useState(false)
    const [filtered, setFiltered] = useState(shops)
    const [url, setUrl] = useState()
    const [activeAcceptModal, setActiveAcceptModal] = useState(false)
    const [type, setType] = useState()
    const fetch = useAuthenticatedFetch();

    const handleOpenAcceptModal = useCallback(
        (value) => {
            setType(value)
            setActiveAcceptModal(!activeAcceptModal)
        },
        [setActiveAcceptModal, activeAcceptModal]
    )

    const {selectedResources, allResourcesSelected, handleSelectionChange, clearSelection} =
        useIndexResourceState(shops)

    const navigate = useNavigate()

    const handleChangeLocation = useCallback(() => {
        navigate('/stores/new')
    }, [])

    const handleSetActive = useCallback(
        (e) => {
            setActive(e.target.innerText)
        },
        [setActive]
    )

    const handleSetActiveSearch = useCallback(
        (e) => {
            setActiveSearch(!activeSearch)
        },
        [activeSearch, setActiveSearch]
    )

    const handleDisabled = (title) => {
        if (active === title) {
            return (
                <Button primary disabled size='slim' onClick={handleSetActive}>
                    {title}
                </Button>
            )
        } else {
            return (
                <Button size='slim' onClick={handleSetActive}>
                    {title}
                </Button>
            )
        }
    }

    const handleChangeActiveModal = useCallback(() => {
        setActiveModal(!activeModal)
        setFiles([])
    }, [activeModal])

    const buttons = [
        {
            content: 'Set active'
        },
        {
            content: 'Set draft'
        },
        {
            content: 'Remove'
        }
    ]

    const handleImportFile = async () => {
        const response = await fetch("/api/import-file");

        const result = await response.json();

        let link = document.createElement("a");
        link.download = result.file_name;
        link.href = result.file_url;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }


    useEffect(() => {
        if (active === 'Active') {
            setFiltered(shops.filter((el) => el.status === 'active'))
        } else if (active === 'Draft') {
            setFiltered(shops.filter((el) => el.status === 'draft'))
        } else {
            setFiltered(shops)
        }
    }, [shops, active])

    useEffect(() => {
        // setUrl()
    }, [setUrl])

    return (
        <>
            {loading ? (
                <SkeletonShopList />
            ) : (
                <Page
                    fullWidth
                    title='Stories'
                    primaryAction={{
                        content: 'Add New',
                        onClick: handleChangeLocation
                    }}
                    secondaryActions={[
                        {
                            onClick: handleImportFile,
                            size: 'slim',
                            download: true,
                            url: url,
                            external: true,
                            content: 'Import',
                            id: 'importFile'
                        },
                        {
                            content: 'Export',
                            onClick: handleChangeActiveModal
                        }
                    ]}>
                    <Card>
                        <Card.Section>
                            <div
                                style={{
                                    display: 'flex',
                                    justifyContent: 'space-between',
                                    height: '36px'
                                }}>
                                {!activeSearch && (
                                    <div
                                        style={{
                                            display: 'flex',
                                            gap: '10px'
                                        }}>
                                        {handleDisabled('All')}
                                        {handleDisabled('Active')}
                                        {handleDisabled('Draft')}
                                    </div>
                                )}
                                <div
                                    style={{
                                        width: '100%',
                                        display: 'flex',
                                        justifyContent: 'flex-end',
                                        gap: '10px'
                                    }}>
                                    <div
                                        style={
                                            activeSearch
                                                ? {
                                                    width: '100%',
                                                    overflow: 'hidden'
                                                }
                                                : {
                                                    width: '0px',
                                                    overflow: 'hidden'
                                                }
                                        }>
                                        <TextField
                                            value={textFieldValue}
                                            onChange={handleTextFieldChange}
                                            placeholder='Searching all stores'
                                            clearButton
                                            onClearButtonClick={
                                                handleClearButtonClick
                                            }
                                            autoComplete='off'
                                        />
                                    </div>
                                    {activeSearch ? (
                                        <Button
                                            plain
                                            onClick={handleSetActiveSearch}
                                            size='large'>
                                            Cancel
                                        </Button>
                                    ) : (
                                        <Button
                                            size='slim'
                                            onClick={handleSetActiveSearch}>
                                            <div style={{display: 'flex'}}>
                                                <div
                                                    style={{
                                                        height: '15px',
                                                        width: '15px'
                                                    }}>
                                                    <Icon
                                                        source={SearchMajor}
                                                        color='primary'
                                                        size='slim'
                                                    />
                                                </div>
                                                <div
                                                    style={{
                                                        height: '15px',
                                                        width: '15px'
                                                    }}>
                                                    <Icon
                                                        source={FilterMajor}
                                                        color='base'
                                                    />
                                                </div>
                                            </div>
                                        </Button>
                                    )}
                                </div>
                            </div>
                        </Card.Section>
                        <Card.Section>
                            <IndexTable
                                itemCount={shops?.length}
                                selectedItemsCount={
                                    allResourcesSelected
                                        ? 'All'
                                        : selectedResources.length
                                }
                                onSelectionChange={handleSelectionChange}
                                headings={[
                                    {title: 'Name'},
                                    {title: 'Status'},
                                    {title: 'Country'},
                                    {title: 'City'},
                                    {title: 'Street'},
                                    {title: 'Phone'}
                                ]}>
                                {filtered &&
                                filtered.map((el, idx) => (
                                    <ShopItem
                                        key={el.id}
                                        item={el}
                                        idx={idx}
                                        selectedResources={
                                            selectedResources
                                        }
                                        setValue={setValue}
                                    />
                                ))}
                            </IndexTable>
                        </Card.Section>
                    </Card>
                    <ModalDropZone
                        files={files}
                        refetchStories={refetchStories}
                        setFiles={setFiles}
                        activeModal={activeModal}
                        handleChangeActiveModal={handleChangeActiveModal}
                    />
                    <ModalEdit
                        handleChange={handleOpenAcceptModal}
                        refetchStories={refetchStories}
                        clearSelection={clearSelection}
                        active={activeAcceptModal}
                        selectedResources={selectedResources}
                        type={type}
                        count={selectedResources.length}
                    />
                    {selectedResources.length > 0 && (
                        <div
                            style={{
                                position: 'absolute',
                                bottom: '20px',
                                width: '100%',
                                display: 'flex',
                                justifyContent: 'center',
                                maxWidth: 'max-content',
                                left: 0,
                                right: 0,
                                margin: '0 auto'
                            }}>
                            <div
                                style={{
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    gap: '10px',
                                    borderRadius: '8px',
                                    background: '#202123',
                                    padding: '16px'
                                }}>
                                {buttons &&
                                buttons.map((el) => (
                                    <CustomButton
                                        key={el.content}
                                        el={el}
                                        handleOpenAcceptModal={
                                            handleOpenAcceptModal
                                        }
                                    />
                                ))}
                            </div>
                        </div>
                    )}
                </Page>
            )}
        </>
    )
}
