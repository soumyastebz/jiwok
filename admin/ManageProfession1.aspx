<%@ Page Language="C#" MasterPageFile="~/Admin/AdminMasterPage.master" AutoEventWireup="true" CodeFile="ManageSubCO.aspx.cs" Inherits="Admin_Career_Options_ManageSubCO" Theme="admin" %>
<asp:Content ID="Content1" ContentPlaceHolderID="cphHeading" Runat="Server">
    Manage sub career options
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="InnerContent" Runat="Server">&nbsp; <br /><table width="100%">
            <tr>
                <td colspan="2">
                    <table  width="100%">
                        <tr>
                            <td class="sqrbrckt" valign="top">
                                [ <asp:HyperLink ID="hlSubList" runat="server" NavigateUrl="~/Admin/Career Options/ListSubCO.aspx" CssClass="manage" 
        >List sub career Options</asp:HyperLink>]
                                -&gt; [ <asp:HyperLink ID="hlSubManage" runat="server" NavigateUrl='~/Admin/Career Options/ManageSubCO.aspx?mode="Add"' CssClass="manage" 
        >Manage Sub career options</asp:HyperLink>]
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td style="width: 307px">
            <asp:label id="lblMessage" runat="server" cssclass="errtext"></asp:label></td><td></td></tr><tr><td style="height: 22px; width: 307px;"><asp:placeholder id="phSUBCO" runat="server"></asp:placeholder></td><td style="height: 22px"></td></tr><tr><td style="height: 22px; width: 307px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <asp:button id="btnSave" runat="server" text="SAVE" onclick="btnSave_Click" /></td><td style="height: 22px">&nbsp; <asp:button id="btnClear" runat="server" text="CLEAR" /></td></tr></table>
</asp:Content>

